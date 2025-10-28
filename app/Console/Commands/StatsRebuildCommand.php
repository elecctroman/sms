<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Core\Console\CommandInterface;
use App\Services\StatisticsService;

class StatsRebuildCommand implements CommandInterface
{
    public function __construct(private readonly StatisticsService $statisticsService)
    {
    }

    public function handle(array $argv): void
    {
        $this->statisticsService->rebuild();
        echo "Statistics rebuilt." . PHP_EOL;
    }
}
