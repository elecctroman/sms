#!/usr/bin/env php
<?php
declare(strict_types=1);

use App\Core\Autoload;
use App\Core\Config;
use App\Services\Suppliers\NessaDemoAdapter;
use App\Services\Suppliers\ProviderXAdapter;

require __DIR__ . '/../app/Core/Autoload.php';
Autoload::register(__DIR__ . '/..');
Config::load(require __DIR__ . '/../config/config.php');

$adapters = [new NessaDemoAdapter(), new ProviderXAdapter()];

echo '[' . date('Y-m-d H:i:s') . "] Tedarikçiler senkronize ediliyor..." . PHP_EOL;
foreach ($adapters as $adapter) {
    foreach ($adapter->getServices() as $service) {
        foreach ($adapter->getCountries() as $country) {
            $quote = $adapter->quote((int) $service['id'], (int) $country['id']);
            echo sprintf("- %s / %s : %.2f %s" . PHP_EOL, $service['name'], $country['name'], $quote['sell_price'], $quote['currency']);
        }
    }
}
