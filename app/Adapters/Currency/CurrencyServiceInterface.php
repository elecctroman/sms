<?php

declare(strict_types=1);

namespace App\Adapters\Currency;

interface CurrencyServiceInterface
{
    public function convert(float $amount, string $from, string $to): float;
}
