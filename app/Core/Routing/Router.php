<?php

declare(strict_types=1);

namespace App\Core\Routing;

use App\Core\Container;
use App\Core\Middlewares\MiddlewareInterface;
use App\Core\Security\CsrfTokenManager;
use App\Core\Session\SessionManager;
use App\Http\Request;
use App\Http\Response;
use Closure;

class Router
{
    /**
     * @var array<int, array{methods: array<int, string>, handler: callable|array{0: string, 1: string}, path: string, name: string|null, middleware: array<int, class-string<MiddlewareInterface>>}>
     */
    private array $routes = [];

    public function __construct(
        private readonly Container $container
    ) {
    }

    public function get(string $path, callable|array $handler, ?string $name = null, array $middleware = []): void
    {
        $this->addRoute(['GET'], $path, $handler, $name, $middleware);
    }

    public function post(string $path, callable|array $handler, ?string $name = null, array $middleware = []): void
    {
        $this->addRoute(['POST'], $path, $handler, $name, $middleware);
    }

    public function match(array $methods, string $path, callable|array $handler, ?string $name = null, array $middleware = []): void
    {
        $this->addRoute($methods, $path, $handler, $name, $middleware);
    }

    /**
     * @param array<int, string> $methods
     * @param array<int, class-string<MiddlewareInterface>> $middleware
     */
    private function addRoute(array $methods, string $path, callable|array $handler, ?string $name, array $middleware): void
    {
        $this->routes[] = [
            'methods' => array_map('strtoupper', $methods),
            'handler' => $handler,
            'path' => $path,
            'name' => $name,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(Request $request): Response
    {
        $allowedMethods = [];
        foreach ($this->routes as $route) {
            $variables = $this->matchPath($request->getPath(), $route['path']);
            if ($variables === null) {
                continue;
            }

            $allowedMethods = array_merge($allowedMethods, $route['methods']);

            if (! in_array($request->getMethod(), $route['methods'], true)) {
                continue;
            }

            return $this->handleRoute($request, $route, $variables);
        }

        if ($allowedMethods !== []) {
            return new Response('Method Not Allowed', Response::HTTP_METHOD_NOT_ALLOWED, [
                'Allow' => implode(', ', array_unique($allowedMethods)),
            ]);
        }

        return new Response('Not Found', Response::HTTP_NOT_FOUND);
    }

    /**
     * @param array{methods: array<int, string>, handler: callable|array{0: string, 1: string}, path: string, name: string|null, middleware: array<int, class-string<MiddlewareInterface>>} $route
     * @param array<string, string> $vars
     */
    private function handleRoute(Request $request, array $route, array $vars): Response
    {
        $handler = $route['handler'];
        $middleware = $route['middleware'];

        $pipeline = $this->buildPipeline($middleware, function (Request $request) use ($handler, $vars): Response {
            if (is_array($handler)) {
                [$class, $method] = $handler;
                $instance = $this->container->make($class);
                return $instance->$method($request, $vars);
            }

            return $handler($request, $vars);
        });

        $this->guardCsrf($request, $route['methods']);

        return $pipeline($request);
    }

    /**
     * @param array<int, string> $methods
     */
    private function guardCsrf(Request $request, array $methods): void
    {
        if (! in_array('POST', $methods, true)) {
            return;
        }

        /** @var CsrfTokenManager $csrf */
        $csrf = $this->container->make(CsrfTokenManager::class);
        /** @var SessionManager $session */
        $session = $this->container->make(SessionManager::class);

        if (! $csrf->validateRequest($request, $session)) {
            throw new \RuntimeException('Invalid CSRF token');
        }
    }

    /**
     * @param array<int, class-string<MiddlewareInterface>> $middlewares
     * @param Closure(Request): Response $destination
     * @return Closure(Request): Response
     */
    private function buildPipeline(array $middlewares, Closure $destination): Closure
    {
        return array_reduce(
            array_reverse($middlewares),
            function (Closure $next, string $middleware) {
                return function (Request $request) use ($next, $middleware): Response {
                    /** @var MiddlewareInterface $instance */
                    $instance = $this->container->make($middleware);
                    return $instance->handle($request, $next);
                };
            },
            $destination
        );
    }

    /**
     * @return array<string, string>|null
     */
    private function matchPath(string $requestPath, string $routePath): ?array
    {
        $requestSegments = $this->segmentPath($requestPath);
        $routeSegments = $this->segmentPath($routePath);

        if (count($requestSegments) !== count($routeSegments)) {
            return null;
        }

        $variables = [];

        foreach ($routeSegments as $index => $segment) {
            $requestSegment = $requestSegments[$index];

            if ($segment === $requestSegment) {
                continue;
            }

            if (preg_match('/^{(.+)}$/', $segment, $matches) === 1) {
                $variables[$matches[1]] = $requestSegment;
                continue;
            }

            return null;
        }

        return $variables;
    }

    /**
     * @return array<int, string>
     */
    private function segmentPath(string $path): array
    {
        $trimmed = trim($path, '/');
        if ($trimmed === '') {
            return [];
        }

        return explode('/', $trimmed);
    }
}
