<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class WalletRepository extends Model
{
    public function findByUser(int $userId): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM wallets WHERE user_id = :id LIMIT 1');
        $stmt->execute(['id' => $userId]);
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);

        return $wallet ?: null;
    }

    public function updateBalance(int $walletId, float $amount): void
    {
        $stmt = $this->db()->prepare('UPDATE wallets SET balance_decimal = :amount, updated_at = NOW() WHERE id = :id');
        $stmt->execute(['amount' => $amount, 'id' => $walletId]);
    }
}
