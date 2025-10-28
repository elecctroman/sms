<?php

declare(strict_types=1);

namespace App\Http;

class Response
{
    public const HTTP_OK = 200;
    public const HTTP_FOUND = 302;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    /** @var array<string, string> */
    private array $headers;

    public function __construct(
        private string $content = '',
        private int $status = self::HTTP_OK,
        array $headers = []
    ) {
        $this->headers = $headers;
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value, true, $this->status);
        }
        echo $this->content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
