<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Middlewares\MiddlewareInterface;
use App\Core\Session\SessionManager;
use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly SessionManager $session)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->session->get('user_id') === null) {
            return new Response('', Response::HTTP_FOUND, ['Location' => '/login']);
        }

        return $next($request);
    }
}
