<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;

final class AuditLogRepository extends Model
{
    public function log(string $action, string $entity, int $entityId, array $meta, ?int $userId): void
    {
        $stmt = $this->db()->prepare('INSERT INTO audit_logs (actor_user_id, action, entity, entity_id, meta, created_at) VALUES (:actor_user_id, :action, :entity, :entity_id, :meta, NOW())');
        $stmt->execute([
            'actor_user_id' => $userId,
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId,
            'meta' => json_encode($meta, JSON_THROW_ON_ERROR),
        ]);
    }
}
