<?php

declare(strict_types=1);

namespace App\Adapters\Supplier;

class ProviderXAdapter implements SupplierAdapterInterface
{
    public function getCountries(): array
    {
        return [
            ['iso2' => 'DE', 'name' => 'Germany', 'enabled' => true],
            ['iso2' => 'FR', 'name' => 'France', 'enabled' => true],
        ];
    }

    public function getOperators(string $countryIso): array
    {
        return [
            ['external_code' => $countryIso . '-GEN', 'name' => 'Generic', 'enabled' => true],
        ];
    }

    public function getServices(): array
    {
        return [
            ['name' => 'Telegram', 'slug' => 'telegram', 'icon' => 'bi-telegram', 'color' => '#26A5E4', 'enabled' => true, 'default_country' => 'DE'],
            ['name' => 'Discord', 'slug' => 'discord', 'icon' => 'bi-discord', 'color' => '#5865F2', 'enabled' => true, 'default_country' => 'FR'],
        ];
    }

    public function quote(string $serviceSlug, string $countryIso, ?string $operatorCode): array
    {
        $base = $serviceSlug === 'telegram' ? 0.95 : 1.10;
        return [[
            'country' => $countryIso,
            'operator' => $operatorCode,
            'buy_price' => $base,
            'sell_price' => $base + 0.65,
            'avg_delivery_seconds' => 75,
            'stock' => 80,
        ]];
    }

    public function order(array $payload): array
    {
        return [
            'external_order_id' => 'PROX-' . random_int(1000, 9999),
            'status' => 'pending',
            'phone_number' => '+49123456789',
        ];
    }

    public function cancel(string $externalOrderId): bool
    {
        return true;
    }

    public function poll(string $externalOrderId): array
    {
        return [
            'status' => 'active',
            'code' => null,
            'received_at' => null,
        ];
    }

    public function getName(): string
    {
        return 'Provider X';
    }
}
