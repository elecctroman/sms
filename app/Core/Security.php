<?php
declare(strict_types=1);

namespace App\Core;

final class Security
{
    public static function enforceHttps(bool $forceHttps): void
    {
        if (!$forceHttps) {
            return;
        }

        $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        if ($isSecure) {
            header('Strict-Transport-Security: max-age=15552000; includeSubDomains');
            return;
        }

        $host = $_SERVER['HTTP_HOST'] ?? '';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: https://' . $host . $requestUri, true, 301);
        exit;
    }

    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function hmac(string $payload, string $secret): string
    {
        return hash_hmac('sha256', $payload, $secret);
    }
}
