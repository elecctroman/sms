<?php

declare(strict_types=1);

namespace App\Core\Session;

class SessionManager
{
    public function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_secure' => true,
                'cookie_httponly' => true,
                'cookie_samesite' => 'Strict',
            ]);
        }
    }

    public function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }
}
