<?php
declare(strict_types=1);

namespace App\Models;

final class Order
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly int $serviceId,
        public readonly string $status,
        public readonly float $charge,
        public readonly string $currency
    ) {
    }
}
