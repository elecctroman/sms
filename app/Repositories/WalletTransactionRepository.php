<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database\Repository;

class WalletTransactionRepository extends Repository
{
    public function rebuildBalances(): void
    {
        $this->query('UPDATE wallets w SET balance_decimal = (
            SELECT COALESCE(SUM(CASE WHEN type IN ("deposit", "bonus") THEN amount_decimal ELSE -amount_decimal END), 0)
            FROM wallet_transactions wt WHERE wt.wallet_id = w.id
        )');
    }
}
