<?php
declare(strict_types=1);

namespace App\Core;

final class Response
{
    private string $content;
    private int $status;
    /** @var array<string, string> */
    private array $headers;

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value, true);
        }
        echo $this->content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
