<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class OrderRepository extends Model
{
    public function recentByUser(int $userId): array
    {
        $stmt = $this->db()->prepare('SELECT * FROM orders WHERE user_id = :id ORDER BY created_at DESC LIMIT 20');
        $stmt->execute(['id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare('INSERT INTO orders (user_id, service_id, country_id, operator_id, supplier_id, external_order_id, phone_number, status, charge, currency, created_at, updated_at, expires_at) VALUES (:user_id, :service_id, :country_id, :operator_id, :supplier_id, :external_order_id, :phone_number, :status, :charge, :currency, NOW(), NOW(), :expires_at)');
        $stmt->execute([
            'user_id' => $data['user_id'],
            'service_id' => $data['service_id'],
            'country_id' => $data['country_id'],
            'operator_id' => $data['operator_id'],
            'supplier_id' => $data['supplier_id'],
            'external_order_id' => $data['external_order_id'],
            'phone_number' => $data['phone_number'],
            'status' => $data['status'],
            'charge' => $data['charge'],
            'currency' => $data['currency'],
            'expires_at' => $data['expires_at'],
        ]);

        return (int) $this->db()->lastInsertId();
    }

    public function findStatus(int $id): ?array
    {
        $stmt = $this->db()->prepare('SELECT status, received_code FROM orders WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}
