<?php
declare(strict_types=1);

namespace App\Lib;

final class SimpleCache
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    public function put(string $key, mixed $value, int $ttl): void
    {
        $payload = ['expires' => time() + $ttl, 'value' => $value];
        file_put_contents($this->path . md5($key) . '.cache', serialize($payload), LOCK_EX);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $file = $this->path . md5($key) . '.cache';
        if (!is_file($file)) {
            return $default;
        }

        $payload = unserialize((string) file_get_contents($file), ['allowed_classes' => false]);
        if (!is_array($payload) || ($payload['expires'] ?? 0) < time()) {
            @unlink($file);
            return $default;
        }

        return $payload['value'];
    }

    public function increment(string $key, int $ttl): int
    {
        $current = (int) $this->get($key, 0) + 1;
        $this->put($key, $current, $ttl);

        return $current;
    }
}
