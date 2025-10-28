<?php

declare(strict_types=1);

namespace App\Core\Logger;

class LoggerFactory
{
    public function __construct(private readonly string $logPath)
    {
    }

    public function make(string $name, string $filename): Logger
    {
        $path = $this->logPath . '/' . ltrim($filename, '/');

        return new Logger($name, $path);
    }

    public function path(string $filename): string
    {
        return $this->logPath . '/' . ltrim($filename, '/');
    }
}
