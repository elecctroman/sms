<?php
declare(strict_types=1);

namespace App\Services\Suppliers;

interface SupplierAdapterInterface
{
    public function getCountries(): array;
    public function getOperators(int $countryId): array;
    public function getServices(): array;
    public function quote(int $serviceId, int $countryId, ?int $operatorId = null): array;
    public function order(array $payload): array;
    public function cancel(string $externalId): bool;
    public function poll(string $externalId): array;
}
