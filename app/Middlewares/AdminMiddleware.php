<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Config;
use App\Core\Response;
use App\Core\Session;

final class AdminMiddleware
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
        $user = $this->session->get('user');
        if ($user === null || !in_array($user['role'], ['owner', 'admin'], true)) {
            return new Response('Yetkisiz erişim', 403);
        }

        return null;
    }
}
