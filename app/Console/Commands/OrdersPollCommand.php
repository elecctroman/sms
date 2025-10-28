<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Core\Console\CommandInterface;
use App\Services\OrderPollingService;

class OrdersPollCommand implements CommandInterface
{
    public function __construct(private readonly OrderPollingService $pollingService)
    {
    }

    public function handle(array $argv): void
    {
        $count = $this->pollingService->pollActiveOrders();
        echo "Polled {$count} orders." . PHP_EOL;
    }
}
