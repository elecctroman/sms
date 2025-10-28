<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Middlewares\MiddlewareInterface;
use App\Core\Session\SessionManager;
use App\Http\Request;
use App\Http\Response;
use Closure;

class AdminMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly SessionManager $session)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->session->get('role') !== 'admin') {
            return new Response('Unauthorized', Response::HTTP_FOUND, ['Location' => '/login']);
        }

        return $next($request);
    }
}
