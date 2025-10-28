<?php

declare(strict_types=1);

namespace App\Core\Logger;

use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;

class LoggerFactory
{
    public function __construct(private readonly string $logPath)
    {
    }

    public function make(string $name, int $level, HandlerInterface $handler): Logger
    {
        $logger = new Logger($name);
        $handler->setLevel($level);
        $logger->pushHandler($handler);
        $logger->pushProcessor(new UidProcessor());

        return $logger;
    }

    public function path(string $filename): string
    {
        return $this->logPath . '/' . $filename;
    }
}
