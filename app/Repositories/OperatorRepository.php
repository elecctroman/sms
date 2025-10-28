<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class OperatorRepository extends Model
{
    public function byCountry(int $countryId): array
    {
        $stmt = $this->db()->prepare('SELECT * FROM operators WHERE enabled = 1 AND country_id = :country ORDER BY name');
        $stmt->execute(['country' => $countryId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
