<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database\Repository;

class OrderRepository extends Repository
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function getActiveOrders(): array
    {
        $statement = $this->query(
            'SELECT id, external_order_id, supplier_id, supplier_adapter FROM orders WHERE status = :status',
            ['status' => 'active']
        );

        return $statement->fetchAll();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateFromSupplier(int $orderId, array $data): void
    {
        $this->query(
            'UPDATE orders SET status = :status, received_code = :code, received_at = :received_at WHERE id = :id',
            [
                'status' => $data['status'],
                'code' => $data['code'],
                'received_at' => $data['received_at'],
                'id' => $orderId,
            ]
        );
    }

    public function recalculateAggregates(): void
    {
        $this->query('UPDATE services SET favorite_count = (SELECT COUNT(*) FROM favorites WHERE service_id = services.id)');
    }
}
