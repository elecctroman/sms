<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class SettingsRepository extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db()->query('SELECT `key`, `value` FROM settings');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $settings = [];
        foreach ($rows as $row) {
            try {
                $settings[$row['key']] = json_decode((string) $row['value'], true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                $settings[$row['key']] = [];
            }
        }

        return $settings;
    }

    public function update(string $key, array $value): void
    {
        $encoded = json_encode($value, JSON_THROW_ON_ERROR);
        $stmt = $this->db()->prepare('INSERT INTO settings (`key`, `value`) VALUES (:key, :value) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
        $stmt->execute(['key' => $key, 'value' => $encoded]);
    }
}
