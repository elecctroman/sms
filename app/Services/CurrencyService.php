<?php
declare(strict_types=1);

namespace App\Services;

final class CurrencyService
{
    /** @var array<string, float> */
    private array $rates = [
        'USD' => 1.0,
        'EUR' => 0.92,
        'TRY' => 28.5,
    ];

    public function convert(float $amount, string $from, string $to): float
    {
        $fromRate = $this->rates[strtoupper($from)] ?? 1.0;
        $toRate = $this->rates[strtoupper($to)] ?? 1.0;

        return round($amount / $fromRate * $toRate, 4);
    }
}
