<?php
declare(strict_types=1);

namespace App\Models;

final class Wallet
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly float $balance,
        public readonly string $currency
    ) {
    }
}
