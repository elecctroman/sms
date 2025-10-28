<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class TicketRepository extends Model
{
    public function byUser(int $userId): array
    {
        $stmt = $this->db()->prepare('SELECT * FROM tickets WHERE user_id = :id ORDER BY created_at DESC');
        $stmt->execute(['id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare('INSERT INTO tickets (user_id, subject, status, priority, created_at, updated_at) VALUES (:user_id, :subject, :status, :priority, NOW(), NOW())');
        $stmt->execute([
            'user_id' => $data['user_id'],
            'subject' => $data['subject'],
            'status' => $data['status'],
            'priority' => $data['priority'],
        ]);

        return (int) $this->db()->lastInsertId();
    }
}
