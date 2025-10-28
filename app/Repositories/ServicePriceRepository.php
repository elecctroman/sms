<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class ServicePriceRepository extends Model
{
    public function quote(int $serviceId, int $countryId, ?int $operatorId = null): ?array
    {
        $sql = 'SELECT * FROM service_prices WHERE service_id = :service AND country_id = :country';
        $params = ['service' => $serviceId, 'country' => $countryId];
        if ($operatorId !== null) {
            $sql .= ' AND (operator_id = :operator OR operator_id IS NULL)';
            $params['operator'] = $operatorId;
        }
        $sql .= ' ORDER BY operator_id IS NULL ASC LIMIT 1';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}
