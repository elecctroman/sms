<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class TicketMessageRepository extends Model
{
    public function messagesForTicket(int $ticketId): array
    {
        $stmt = $this->db()->prepare('SELECT * FROM ticket_messages WHERE ticket_id = :id ORDER BY created_at ASC');
        $stmt->execute(['id' => $ticketId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): void
    {
        $stmt = $this->db()->prepare('INSERT INTO ticket_messages (ticket_id, user_id, message, attachments, created_at) VALUES (:ticket_id, :user_id, :message, :attachments, NOW())');
        $stmt->execute([
            'ticket_id' => $data['ticket_id'],
            'user_id' => $data['user_id'],
            'message' => $data['message'],
            'attachments' => json_encode($data['attachments'] ?? [], JSON_THROW_ON_ERROR),
        ]);
    }
}
