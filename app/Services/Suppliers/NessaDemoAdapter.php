<?php
declare(strict_types=1);

namespace App\Services\Suppliers;

final class NessaDemoAdapter implements SupplierAdapterInterface
{
    public function getCountries(): array
    {
        return [
            ['id' => 1, 'iso2' => 'TR', 'name' => 'Türkiye'],
            ['id' => 2, 'iso2' => 'US', 'name' => 'Amerika Birleşik Devletleri'],
        ];
    }

    public function getOperators(int $countryId): array
    {
        return [
            ['id' => 1, 'name' => 'Turkcell', 'country_id' => $countryId],
            ['id' => 2, 'name' => 'Vodafone', 'country_id' => $countryId],
        ];
    }

    public function getServices(): array
    {
        return [
            ['id' => 1, 'name' => 'WhatsApp'],
            ['id' => 2, 'name' => 'Telegram'],
        ];
    }

    public function quote(int $serviceId, int $countryId, ?int $operatorId = null): array
    {
        return [
            'buy_price' => 0.25,
            'sell_price' => 0.45,
            'avg_delivery_seconds' => 120,
            'stock' => 25,
            'currency' => 'USD',
            'operator_id' => $operatorId,
            'service_id' => $serviceId,
            'country_id' => $countryId,
        ];
    }

    public function order(array $payload): array
    {
        return [
            'external_order_id' => 'NESSA-' . random_int(1000, 9999),
            'phone_number' => '+90555' . random_int(1000000, 9999999),
            'status' => 'active',
        ];
    }

    public function cancel(string $externalId): bool
    {
        return true;
    }

    public function poll(string $externalId): array
    {
        return [
            'status' => 'completed',
            'code' => (string) random_int(100000, 999999),
        ];
    }
}
