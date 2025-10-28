<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database\Repository;

class OperatorRepository extends Repository
{
    /**
     * @param array<int, array<string, mixed>> $operators
     */
    public function sync(string $countryIso, array $operators): void
    {
        foreach ($operators as $operator) {
            $this->query(
                'INSERT INTO operators (country_id, name, external_code, enabled)
                VALUES ((SELECT id FROM countries WHERE iso2 = :iso2), :name, :external_code, :enabled)
                ON DUPLICATE KEY UPDATE name = VALUES(name), enabled = VALUES(enabled)',
                [
                    'iso2' => $countryIso,
                    'name' => $operator['name'],
                    'external_code' => $operator['external_code'],
                    'enabled' => $operator['enabled'] ?? true,
                ]
            );
        }
    }
}
