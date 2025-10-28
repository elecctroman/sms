<?php

declare(strict_types=1);

namespace App\Core\Routing;

use App\Core\Container;
use App\Core\Middlewares\MiddlewareInterface;
use App\Core\Security\CsrfTokenManager;
use App\Core\Session\SessionManager;
use Closure;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function FastRoute\simpleDispatcher;

class Router
{
    /** @var array<int, array{methods: array<int, string>, handler: callable|array{0: string, 1: string}, path: string, name: string|null, middleware: array<int, class-string<MiddlewareInterface>>}> */
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
            'methods' => $methods,
            'handler' => $handler,
            'path' => $path,
            'name' => $name,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(Request $request): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector): void {
            foreach ($this->routes as $route) {
                $collector->addRoute($route['methods'], $route['path'], [
                    'handler' => $route['handler'],
                    'path' => $route['path'],
                ]);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return new Response('Not Found', Response::HTTP_NOT_FOUND);
            case Dispatcher::METHOD_NOT_ALLOWED:
                return new Response('Method Not Allowed', Response::HTTP_METHOD_NOT_ALLOWED);
            case Dispatcher::FOUND:
                /** @var array{handler: callable|array{0: string, 1: string}, path: string} $route */
                $route = $routeInfo[1];
                /** @var array<string, string> $vars */
                $vars = $routeInfo[2];
                return $this->handleRoute($request, $route, $vars);
        }

        return new Response('Error', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param array{handler: callable|array{0: string, 1: string}, path: string} $route
     * @param array<string, string> $vars
     */
    private function handleRoute(Request $request, array $route, array $vars): Response
    {
        $handler = $route['handler'];

        $matchingRoute = $this->findRouteDefinition($route['path'], $request->getMethod());
        $middleware = $matchingRoute['middleware'] ?? [];

        $pipeline = $this->buildPipeline($middleware, function (Request $request) use ($handler, $vars): Response {
            if (is_array($handler)) {
                [$class, $method] = $handler;
                $instance = $this->container->make($class);
                return $instance->$method($request, $vars);
            }

            return $handler($request, $vars);
        });

        $this->guardCsrf($request, $matchingRoute['methods'] ?? []);

        return $pipeline($request);
    }

    private function findRouteDefinition(string $path, string $method): ?array
    {
        foreach ($this->routes as $route) {
            if ($route['path'] === $path && in_array($method, $route['methods'], true)) {
                return $route;
            }
        }

        return null;
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
}
