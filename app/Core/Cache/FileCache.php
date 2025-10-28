<?php

declare(strict_types=1);

namespace App\Core\Cache;

class FileCache implements CacheInterface
{
    public function __construct(private readonly string $path)
    {
        if (! is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $file = $this->fileName($key);
        if (! file_exists($file)) {
            return $default;
        }

        $content = json_decode((string) file_get_contents($file), true, flags: JSON_THROW_ON_ERROR);

        if (($content['expires_at'] ?? 0) < time()) {
            unlink($file);
            return $default;
        }

        return $content['value'];
    }

    public function put(string $key, mixed $value, int $seconds): void
    {
        $payload = [
            'value' => $value,
            'expires_at' => time() + $seconds,
        ];

        file_put_contents($this->fileName($key), json_encode($payload, JSON_THROW_ON_ERROR));
    }

    public function forget(string $key): void
    {
        $file = $this->fileName($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    private function fileName(string $key): string
    {
        return $this->path . '/' . sha1($key) . '.cache';
    }
}
