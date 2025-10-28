<?php

declare(strict_types=1);

namespace App\Core\Queue;

use App\Core\Config\ConfigRepository;

class QueueManager
{
    public function __construct(private readonly ConfigRepository $config)
    {
    }

    public function connection(?string $name = null): QueueInterface
    {
        $name ??= $this->config->get('queue.default', 'file');

        return match ($name) {
            'file' => new FileQueue($this->config->get('queue.connections.file.path', __DIR__ . '/../../storage/queue')),
            default => throw new \RuntimeException('Unsupported queue connection'),
        };
    }
}
