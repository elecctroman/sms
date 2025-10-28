<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Config;
use App\Core\Response;
use App\Core\Session;

final class AuthMiddleware
{
    private Session $session;

    public function __construct()
    {
        $this->session = new Session((string) Config::get('security.session_name', 'app_session'), __DIR__ . '/../../storage/sessions');
    }

    /**
     * @param array<string, mixed> $request
     */
    public function __invoke(array $request): ?Response
    {
        if ($this->session->get('user') === null) {
            $response = new Response('', 302);
            $response->setHeader('Location', '/login');

            return $response;
        }

        return null;
    }
}
