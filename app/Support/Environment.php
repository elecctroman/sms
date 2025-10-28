<?php

declare(strict_types=1);

namespace App\Support;

final class Environment
{
    public static function load(string $rootPath): void
    {
        $path = rtrim($rootPath, '/\\') . '/.env';
        if (! is_file($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            [$key, $value] = $parts;
            $key = trim($key);
            $value = trim($value);

            if ($value !== '') {
                if ($value[0] === '"' && str_ends_with($value, '"')) {
                    $value = substr($value, 1, -1);
                } elseif ($value[0] === '\'' && str_ends_with($value, '\'')) {
                    $value = substr($value, 1, -1);
                }
            }

            if (! array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
            }

            if (! array_key_exists($key, $_SERVER)) {
                $_SERVER[$key] = $value;
            }
        }
    }
}
