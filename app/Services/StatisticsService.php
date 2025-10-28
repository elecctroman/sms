<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Model;
use PDO;

final class StatisticsService extends Model
{
    public function revenueByDay(): array
    {
        $stmt = $this->db()->query('SELECT DATE(created_at) as day, SUM(charge) as total FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY day ORDER BY day');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function revenueByMonth(): array
    {
        $stmt = $this->db()->query('SELECT DATE_FORMAT(created_at, "%Y-%m") as month, SUM(charge) as total FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY month ORDER BY month');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
