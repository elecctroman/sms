<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use DateTimeImmutable;
use PDO;

final class UserRepository extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare('INSERT INTO users (name, email, pass_hash, phone, role, status, created_at, updated_at) VALUES (:name, :email, :pass_hash, :phone, :role, :status, :created_at, :updated_at)');
        $now = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'pass_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone' => $data['phone'],
            'role' => $data['role'] ?? 'user',
            'status' => $data['status'] ?? 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return (int) $this->db()->lastInsertId();
    }

    public function updateLogin(int $id): void
    {
        $stmt = $this->db()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function all(): array
    {
        $stmt = $this->db()->query('SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
