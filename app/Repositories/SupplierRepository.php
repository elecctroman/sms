<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class SupplierRepository extends Model
{
    public function allEnabled(): array
    {
        $stmt = $this->db()->query('SELECT * FROM suppliers WHERE enabled = 1 ORDER BY name');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
