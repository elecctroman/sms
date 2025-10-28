<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Adapters\Supplier\SupplierAdapterInterface;
use App\Core\Database\Repository;

class ServicePriceRepository extends Repository
{
    /**
     * @param array<int, array<string, mixed>> $services
     */
    public function syncPrices(SupplierAdapterInterface $adapter, array $services): void
    {
        foreach ($services as $service) {
            $quotes = $adapter->quote(
                $service['slug'],
                $service['default_country'] ?? 'TR',
                $service['default_operator'] ?? null
            );

            foreach ($quotes as $quote) {
                $this->query(
                    'INSERT INTO service_prices (service_id, country_id, operator_id, buy_price, sell_price, avg_delivery_seconds, stock, supplier_id, last_sync_at)
                    VALUES (
                        (SELECT id FROM services WHERE slug = :slug),
                        (SELECT id FROM countries WHERE iso2 = :country),
                        (SELECT id FROM operators WHERE external_code = :operator LIMIT 1),
                        :buy_price,
                        :sell_price,
                        :avg_delivery,
                        :stock,
                        (SELECT id FROM suppliers WHERE name = :supplier LIMIT 1),
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE buy_price = VALUES(buy_price), sell_price = VALUES(sell_price), stock = VALUES(stock), last_sync_at = VALUES(last_sync_at)',
                    [
                        'slug' => $service['slug'],
                        'country' => $quote['country'],
                        'operator' => $quote['operator'],
                        'buy_price' => $quote['buy_price'],
                        'sell_price' => $quote['sell_price'],
                        'avg_delivery' => $quote['avg_delivery_seconds'],
                        'stock' => $quote['stock'],
                        'supplier' => $adapter->getName(),
                    ]
                );
            }
        }
    }
}
