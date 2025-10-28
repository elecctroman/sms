<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class AnnouncementRepository extends Model
{
    public function latest(int $limit = 5): array
    {
        $stmt = $this->db()->prepare('SELECT * FROM announcements WHERE active = 1 ORDER BY published_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
