<?php

declare(strict_types=1);

namespace App\Core\Logger;

class Logger
{
    public function __construct(
        private readonly string $name,
        private readonly string $path
    ) {
        $directory = dirname($this->path);
        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
    }

    /**
     * @param array<string, mixed> $context
     */
    public function log(string $level, string $message, array $context = []): void
    {
        $contextString = '';
        if ($context !== []) {
            $contextString = ' ' . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        }

        $line = sprintf('[%s] %s.%s: %s%s%s', date('c'), $this->name, strtoupper($level), $message, $contextString, PHP_EOL);
        file_put_contents($this->path, $line, FILE_APPEND);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }
}
