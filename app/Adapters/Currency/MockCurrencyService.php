<?php

declare(strict_types=1);

namespace App\Adapters\Currency;

class MockCurrencyService implements CurrencyServiceInterface
{
    public function __construct(private readonly string $base = 'USD')
    {
    }

    public function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $rates = [
            'USD' => 1.0,
            'TRY' => 27.5,
            'EUR' => 0.93,
        ];

        $fromRate = $rates[strtoupper($from)] ?? 1.0;
        $toRate = $rates[strtoupper($to)] ?? 1.0;

        return $amount * ($toRate / $fromRate);
    }
}
