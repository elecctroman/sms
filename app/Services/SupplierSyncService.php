<?php

declare(strict_types=1);

namespace App\Services;

use App\Adapters\Supplier\SupplierAdapterInterface;
use App\Repositories\CountryRepository;
use App\Repositories\OperatorRepository;
use App\Repositories\ServicePriceRepository;
use App\Repositories\ServiceRepository;

class SupplierSyncService
{
    /** @param iterable<SupplierAdapterInterface> $adapters */
    public function __construct(
        private readonly iterable $adapters,
        private readonly CountryRepository $countryRepository,
        private readonly OperatorRepository $operatorRepository,
        private readonly ServiceRepository $serviceRepository,
        private readonly ServicePriceRepository $servicePriceRepository
    ) {
    }

    public function syncAll(): void
    {
        foreach ($this->adapters as $adapter) {
            $countries = $adapter->getCountries();
            $this->countryRepository->sync($countries);

            foreach ($countries as $country) {
                $operators = $adapter->getOperators($country['iso2']);
                $this->operatorRepository->sync($country['iso2'], $operators);
            }

            $services = $adapter->getServices();
            $this->serviceRepository->sync($services);
            $this->servicePriceRepository->syncPrices($adapter, $services);
        }
    }
}
