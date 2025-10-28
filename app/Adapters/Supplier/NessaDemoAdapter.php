<?php

declare(strict_types=1);

namespace App\Adapters\Supplier;

class NessaDemoAdapter implements SupplierAdapterInterface
{
    public function getCountries(): array
    {
        return [
            ['iso2' => 'TR', 'name' => 'Türkiye', 'enabled' => true],
            ['iso2' => 'US', 'name' => 'United States', 'enabled' => true],
        ];
    }

    public function getOperators(string $countryIso): array
    {
        return match ($countryIso) {
            'TR' => [
                ['external_code' => 'TURKCELL', 'name' => 'Turkcell', 'enabled' => true],
                ['external_code' => 'VODAFONE', 'name' => 'Vodafone', 'enabled' => true],
            ],
            'US' => [
                ['external_code' => 'ATT', 'name' => 'AT&T', 'enabled' => true],
            ],
            default => [],
        };
    }

    public function getServices(): array
    {
        return [
            ['name' => 'WhatsApp', 'slug' => 'whatsapp', 'icon' => 'bi-whatsapp', 'color' => '#25D366', 'enabled' => true, 'default_country' => 'TR'],
            ['name' => 'Instagram', 'slug' => 'instagram', 'icon' => 'bi-instagram', 'color' => '#E1306C', 'enabled' => true, 'default_country' => 'US'],
        ];
    }

    public function quote(string $serviceSlug, string $countryIso, ?string $operatorCode): array
    {
        return [[
            'country' => $countryIso,
            'operator' => $operatorCode,
            'buy_price' => 1.25,
            'sell_price' => 1.99,
            'avg_delivery_seconds' => 45,
            'stock' => 120,
        ]];
    }

    public function order(array $payload): array
    {
        return [
            'external_order_id' => 'NESSA-' . random_int(1000, 9999),
            'status' => 'pending',
            'phone_number' => '+901234567890',
        ];
    }

    public function cancel(string $externalOrderId): bool
    {
        return true;
    }

    public function poll(string $externalOrderId): array
    {
        return [
            'status' => 'completed',
            'code' => '123456',
            'received_at' => date('Y-m-d H:i:s'),
        ];
    }

    public function getName(): string
    {
        return 'Nessa Demo';
    }
}
