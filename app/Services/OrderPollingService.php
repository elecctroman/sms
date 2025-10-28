<?php

declare(strict_types=1);

namespace App\Services;

use App\Adapters\Supplier\SupplierAdapterInterface;
use App\Repositories\OrderRepository;

class OrderPollingService
{
    /** @param iterable<SupplierAdapterInterface> $adapters */
    public function __construct(
        private readonly iterable $adapters,
        private readonly OrderRepository $orderRepository
    ) {
    }

    public function pollActiveOrders(): int
    {
        $orders = $this->orderRepository->getActiveOrders();
        $count = 0;

        foreach ($orders as $order) {
            $adapter = $this->resolveAdapter($order['supplier_adapter']);
            if ($adapter === null) {
                continue;
            }

            $result = $adapter->poll($order['external_order_id']);
            $this->orderRepository->updateFromSupplier($order['id'], $result);
            $count++;
        }

        return $count;
    }

    private function resolveAdapter(string $class): ?SupplierAdapterInterface
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter::class === $class) {
                return $adapter;
            }
        }

        return null;
    }
}
