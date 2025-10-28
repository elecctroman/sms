<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Core\Console\CommandInterface;
use App\Services\SupplierSyncService;

class SyncSuppliersCommand implements CommandInterface
{
    public function __construct(private readonly SupplierSyncService $service)
    {
    }

    public function handle(array $argv): void
    {
        $this->service->syncAll();
        echo "Suppliers synchronized successfully." . PHP_EOL;
    }
}
