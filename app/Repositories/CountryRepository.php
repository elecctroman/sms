<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database\Repository;

class CountryRepository extends Repository
{
    /**
     * @param array<int, array<string, mixed>> $countries
     */
    public function sync(array $countries): void
    {
        foreach ($countries as $country) {
            $this->query(
                'INSERT INTO countries (iso2, name, enabled) VALUES (:iso2, :name, :enabled)
                ON DUPLICATE KEY UPDATE name = VALUES(name), enabled = VALUES(enabled)',
                [
                    'iso2' => $country['iso2'],
                    'name' => $country['name'],
                    'enabled' => $country['enabled'] ?? true,
                ]
            );
        }
    }
}
