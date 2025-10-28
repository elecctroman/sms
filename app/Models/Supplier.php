<?php
declare(strict_types=1);

namespace App\Models;

final class Supplier
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $currency,
        public readonly float $priceMarkup
    ) {
    }
}
