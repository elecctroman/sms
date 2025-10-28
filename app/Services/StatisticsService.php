<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\WalletTransactionRepository;

class StatisticsService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly WalletTransactionRepository $walletTransactionRepository
    ) {
    }

    public function rebuild(): void
    {
        $this->orderRepository->recalculateAggregates();
        $this->walletTransactionRepository->rebuildBalances();
    }
}
