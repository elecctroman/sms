<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class BlogRepository extends Model
{
    public function latest(int $limit = 6): array
    {
        $stmt = $this->db()->prepare('SELECT * FROM blog_posts WHERE active = 1 ORDER BY published_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM blog_posts WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        return $post ?: null;
    }
}
