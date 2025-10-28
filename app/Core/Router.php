<?php
declare(strict_types=1);

namespace App\Core;

use PDOException;

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

                try {
                    $reflection = new \ReflectionMethod($controller, $action);
                    $arguments = $reflection->getNumberOfParameters() > 0 ? [$request] : [];

                    return $reflection->invokeArgs($controller, $arguments);
                } catch (PDOException $exception) {
                    return $this->databaseErrorResponse($exception);
                }
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

    private function databaseErrorResponse(PDOException $exception): Response
    {
        error_log('[Database] ' . $exception->getMessage());

        $message = 'Veritabanı hatası oluştu.';
        $hints = [
            'config/config.php dosyasındaki veritabanı kullanıcı adı ve şifresini doğrulayın.',
            'database/schema.sql ve database/seed.sql dosyalarını çalıştırdığınızdan emin olun.',
        ];

        $code = $exception->getCode();
        $details = strtolower($exception->getMessage());
        $codeString = is_string($code) ? strtolower($code) : '';
        if (($codeString !== '' && str_starts_with($codeString, '42s02')) || str_contains($details, 'base table or view not found')) {
            $message = 'Veritabanı tabloları eksik görünüyor.';
            $hints = [
                'Kurulum sihirbazını yeniden çalıştırın veya database/schema.sql dosyasını phpMyAdmin üzerinden içe aktarın.',
                'Kurulumdan sonra install klasörünün yeniden adlandırıldığından emin olun.',
            ];
        } elseif (str_contains($details, 'access denied')) {
            $message = 'Veritabanına bağlanılamadı.';
            $hints = [
                'config/config.php içindeki veritabanı kullanıcı adı, şifre ve yetkilerini kontrol edin.',
                'Kullanıcıya uzak bağlantı yetkisi gerekiyorsa cPanel üzerinden gerekli izinleri verin.',
            ];
        }

        $html = '<!DOCTYPE html><html lang="tr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
        $html .= '<title>Veritabanı Hatası</title><style>body{font-family:Inter,system-ui,-apple-system,sans-serif;background:#f9fafb;color:#111827;padding:40px;}';
        $html .= '.card{max-width:640px;margin:0 auto;background:#fff;border-radius:12px;padding:32px;box-shadow:0 10px 40px rgba(15,23,42,0.08);}';
        $html .= 'h1{font-size:24px;margin-bottom:16px;}ul{margin:0;padding-left:18px;}li{margin-bottom:8px;}code{display:block;margin-top:16px;padding:12px;background:#0f172a;color:#e2e8f0;border-radius:8px;font-size:14px;word-break:break-all;}';
        $html .= '</style></head><body><div class="card"><h1>' . $message . '</h1><p>Platform veritabanına erişemedi. Aşağıdaki adımları kontrol ederek sorunu giderebilirsiniz:</p><ul>';
        foreach ($hints as $hint) {
            $html .= '<li>' . htmlspecialchars($hint, ENT_QUOTES, 'UTF-8') . '</li>';
        }

        $html .= '</ul><code>' . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8') . '</code></div></body></html>';

        return new Response($html, 500, ['Content-Type' => 'text/html; charset=UTF-8']);
    }
}
