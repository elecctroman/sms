<?php

declare(strict_types=1);

namespace App\Core\Queue;

interface QueueInterface
{
    public function push(string $job, array $data = []): void;

    public function pop(): ?array;
}
