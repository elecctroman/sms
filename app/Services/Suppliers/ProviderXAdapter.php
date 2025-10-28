<?php
declare(strict_types=1);

namespace App\Services\Suppliers;

final class ProviderXAdapter implements SupplierAdapterInterface
{
    public function getCountries(): array
    {
        return [
            ['id' => 3, 'iso2' => 'DE', 'name' => 'Almanya'],
            ['id' => 4, 'iso2' => 'GB', 'name' => 'Birleşik Krallık'],
        ];
    }

    public function getOperators(int $countryId): array
    {
        return [
            ['id' => 3, 'name' => 'Deutsche Telekom', 'country_id' => $countryId],
            ['id' => 4, 'name' => 'O2', 'country_id' => $countryId],
        ];
    }

    public function getServices(): array
    {
        return [
            ['id' => 3, 'name' => 'Discord'],
            ['id' => 4, 'name' => 'Instagram'],
        ];
    }

    public function quote(int $serviceId, int $countryId, ?int $operatorId = null): array
    {
        $base = 0.65;
        if ($countryId === 4) {
            $base = 0.72;
        }

        return [
            'buy_price' => $base,
            'sell_price' => $base + 0.35,
            'avg_delivery_seconds' => 180,
            'stock' => 12,
            'currency' => 'EUR',
            'operator_id' => $operatorId,
            'service_id' => $serviceId,
            'country_id' => $countryId,
        ];
    }

    public function order(array $payload): array
    {
        return [
            'external_order_id' => 'PROVX-' . random_int(1000, 9999),
            'phone_number' => '+49151' . random_int(1000000, 9999999),
            'status' => 'pending',
        ];
    }

    public function cancel(string $externalId): bool
    {
        return true;
    }

    public function poll(string $externalId): array
    {
        return [
            'status' => 'active',
            'code' => null,
        ];
    }
}
