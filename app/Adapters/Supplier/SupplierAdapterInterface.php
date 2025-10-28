<?php

declare(strict_types=1);

namespace App\Adapters\Supplier;

interface SupplierAdapterInterface
{
    /**
     * @return array<int, array{iso2: string, name: string, enabled: bool}>
     */
    public function getCountries(): array;

    /**
     * @return array<int, array{external_code: string, name: string, enabled: bool}>
     */
    public function getOperators(string $countryIso): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getServices(): array;

    /**
     * @return array<int, array{country: string, operator: ?string, buy_price: float, sell_price: float, avg_delivery_seconds: int, stock: int}>
     */
    public function quote(string $serviceSlug, string $countryIso, ?string $operatorCode): array;

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function order(array $payload): array;

    public function cancel(string $externalOrderId): bool;

    /**
     * @return array<string, mixed>
     */
    public function poll(string $externalOrderId): array;

    public function getName(): string;
}
