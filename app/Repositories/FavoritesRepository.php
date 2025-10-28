<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class FavoritesRepository extends Model
{
    public function allForUser(int $userId): array
    {
        $stmt = $this->db()->prepare('SELECT s.* FROM favorites f INNER JOIN services s ON s.id = f.service_id WHERE f.user_id = :id');
        $stmt->execute(['id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
