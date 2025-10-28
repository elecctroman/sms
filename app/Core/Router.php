<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<int, array<string, mixed>>> */
    private array $routes = [];

    /** @var array<string, callable> */
    private array $middlewares = [];

    public function add(string $method, string $path, array $handler, ?string $name = null, array $middleware = []): void
    {
        $this->routes[strtoupper($method)][] = [
            'path' => $path,
            'handler' => $handler,
            'name' => $name,
            'middleware' => $middleware,
        ];
    }

    public function registerMiddleware(string $name, callable $callable): void
    {
        $this->middlewares[$name] = $callable;
    }

    public function dispatch(string $method, string $uri): Response
    {
        $method = strtoupper($method);
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';

        foreach ($this->routes[$method] ?? [] as $route) {
            $pattern = $this->compilePath($route['path']);
            if (preg_match($pattern, $uri, $matches) === 1) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $params[$key] = $value;
                    }
                }

                $params['route'] = $route['name'] ?? $route['path'];
                $request = ['query' => $_GET, 'body' => $_POST, 'params' => $params];
                $response = $this->runMiddlewares($route['middleware'], $request);
                if ($response instanceof Response) {
                    return $response;
                }

                [$class, $action] = $route['handler'];
                $controller = $class;
                if (is_string($class)) {
                    $controller = new $class(...$this->resolveControllerDependencies());
                }

                $reflection = new \ReflectionMethod($controller, $action);
                $arguments = $reflection->getNumberOfParameters() > 0 ? [$request] : [];

                return $reflection->invokeArgs($controller, $arguments);
            }
        }

        return new Response('Sayfa bulunamadı', 404);
    }

    private function runMiddlewares(array $middlewares, array $request): ?Response
    {
        foreach ($middlewares as $name) {
            if (!isset($this->middlewares[$name])) {
                continue;
            }

            $result = ($this->middlewares[$name])($request);
            if ($result instanceof Response) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @return array<int, mixed>
     */
    private function resolveControllerDependencies(): array
    {
        $session = new Session((string) Config::get('security.session_name', 'app_session'), __DIR__ . '/../../storage/sessions');
        $csrf = new CSRF($session, (int) Config::get('security.csrf_lifetime', 900));
        $view = new View(__DIR__ . '/../Views');
        $view->share('app', Config::get('app'));
        $view->share('csrf', $csrf);
        $view->share('session', $session);
        $translator = new \App\Services\TranslationService();
        $view->share('trans', [$translator, 'trans']);

        return [$view, $session, $csrf];
    }

    private function compilePath(string $path): string
    {
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[\\w-]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
