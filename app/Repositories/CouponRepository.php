<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class CouponRepository extends Model
{
    public function findActive(string $code): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM coupons WHERE code = :code AND active = 1 AND (usage_limit IS NULL OR used_count < usage_limit) AND (starts_at IS NULL OR starts_at <= NOW()) AND (ends_at IS NULL OR ends_at >= NOW()) LIMIT 1');
        $stmt->execute(['code' => $code]);
        $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

        return $coupon ?: null;
    }
}
