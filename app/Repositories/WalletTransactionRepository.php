<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Model;
use PDO;

final class WalletTransactionRepository extends Model
{
    public function log(array $data): void
    {
        $stmt = $this->db()->prepare('INSERT INTO wallet_transactions (wallet_id, type, amount_decimal, balance_after, meta, created_at) VALUES (:wallet_id, :type, :amount_decimal, :balance_after, :meta, NOW())');
        $stmt->execute([
            'wallet_id' => $data['wallet_id'],
            'type' => $data['type'],
            'amount_decimal' => $data['amount_decimal'],
            'balance_after' => $data['balance_after'],
            'meta' => json_encode($data['meta'], JSON_THROW_ON_ERROR),
        ]);
    }
}
