<?php

declare(strict_types=1);

namespace App\Http;

class Request
{
    /** @param array<string, mixed> $query */
    /** @param array<string, mixed> $body */
    /** @param array<string, mixed> $cookies */
    /** @param array<string, mixed> $files */
    /** @param array<string, mixed> $server */
    private function __construct(
        private readonly array $query,
        private readonly array $body,
        private readonly array $cookies,
        private readonly array $files,
        private readonly array $server
    ) {
    }

    public static function fromGlobals(): self
    {
        return new self($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function getPath(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        return $path !== null ? (string) $path : '/';
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function getServer(string $key, mixed $default = null): mixed
    {
        return $this->server[$key] ?? $default;
    }

    /**
     * @return array<string, mixed>
     */
    public function cookies(): array
    {
        return $this->cookies;
    }

    /**
     * @return array<string, mixed>
     */
    public function files(): array
    {
        return $this->files;
    }
}
