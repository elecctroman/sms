<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database\Repository;

class ServiceRepository extends Repository
{
    /**
     * @param array<int, array<string, mixed>> $services
     */
    public function sync(array $services): void
    {
        foreach ($services as $service) {
            $this->query(
                'INSERT INTO services (name, slug, icon, color, enabled)
                VALUES (:name, :slug, :icon, :color, :enabled)
                ON DUPLICATE KEY UPDATE icon = VALUES(icon), color = VALUES(color), enabled = VALUES(enabled)',
                [
                    'name' => $service['name'],
                    'slug' => $service['slug'],
                    'icon' => $service['icon'] ?? 'bi-shield-check',
                    'color' => $service['color'] ?? '#4f46e5',
                    'enabled' => $service['enabled'] ?? true,
                ]
            );
        }
    }
}
