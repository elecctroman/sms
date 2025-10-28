<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class ServiceRepository extends Model
{
    public function search(string $term): array
    {
        $stmt = $this->db()->prepare('SELECT * FROM services WHERE enabled = 1 AND name LIKE :term ORDER BY favorite_count DESC');
        $stmt->execute(['term' => '%' . $term . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allEnabled(): array
    {
        $stmt = $this->db()->query('SELECT * FROM services WHERE enabled = 1 ORDER BY favorite_count DESC');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
