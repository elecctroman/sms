<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class CountryRepository extends Model
{
    public function enabled(): array
    {
        $stmt = $this->db()->query('SELECT * FROM countries WHERE enabled = 1 ORDER BY name');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
