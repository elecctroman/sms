<?php

declare(strict_types=1);

namespace App\Core\Middlewares;

use App\Http\Request;
use App\Http\Response;
use Closure;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response;
}
