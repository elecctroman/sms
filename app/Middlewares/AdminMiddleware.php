<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Middlewares\MiddlewareInterface;
use App\Core\Session\SessionManager;
use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly SessionManager $session)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $role = $this->session->get('role');
        if ($role === null || ! in_array($role, ['owner', 'admin', 'support', 'finance', 'readonly', 'reseller'], true)) {
            return new Response('Forbidden', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
