<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\WalletRepository;
use App\Repositories\WalletTransactionRepository;
use App\Services\Suppliers\NessaDemoAdapter;
use App\Services\Suppliers\ProviderXAdapter;
use App\Services\Suppliers\SupplierAdapterInterface;

final class SmsService
{
    /** @var array<string, SupplierAdapterInterface> */
    private array $adapters;
    private OrderRepository $orders;
    private WalletRepository $wallets;
    private WalletTransactionRepository $transactions;

    public function __construct()
    {
        $this->adapters = [
            'nessa' => new NessaDemoAdapter(),
            'providerx' => new ProviderXAdapter(),
        ];
        $this->orders = new OrderRepository();
        $this->wallets = new WalletRepository();
        $this->transactions = new WalletTransactionRepository();
    }

    public function placeOrder(array $payload): array
    {
        $adapter = $this->adapters[$payload['supplier']] ?? null;
        if (!$adapter instanceof SupplierAdapterInterface) {
            throw new \RuntimeException('Tedarikçi bulunamadı');
        }

        $quote = $adapter->quote($payload['service_id'], $payload['country_id'], $payload['operator_id']);
        $wallet = $this->wallets->findByUser($payload['user_id']);
        if ($wallet === null) {
            throw new \RuntimeException('Cüzdan bulunamadı');
        }

        if ((float) $wallet['balance_decimal'] < $quote['sell_price']) {
            throw new \RuntimeException('Yetersiz bakiye');
        }

        $result = $adapter->order($payload);
        $newBalance = (float) $wallet['balance_decimal'] - (float) $quote['sell_price'];
        $this->wallets->updateBalance((int) $wallet['id'], $newBalance);
        $this->transactions->log([
            'wallet_id' => $wallet['id'],
            'type' => 'withdraw',
            'amount_decimal' => $quote['sell_price'],
            'balance_after' => $newBalance,
            'meta' => ['reason' => 'order', 'service_id' => $payload['service_id']],
        ]);

        $supplierId = $payload['supplier_id'] ?? ($payload['supplier'] === 'providerx' ? 2 : 1);

        $orderId = $this->orders->create([
            'user_id' => $payload['user_id'],
            'service_id' => $payload['service_id'],
            'country_id' => $payload['country_id'],
            'operator_id' => $payload['operator_id'],
            'supplier_id' => $supplierId,
            'external_order_id' => $result['external_order_id'],
            'phone_number' => $result['phone_number'],
            'status' => $result['status'],
            'charge' => $quote['sell_price'],
            'currency' => $quote['currency'],
            'expires_at' => date('Y-m-d H:i:s', time() + 600),
        ]);

        return ['id' => $orderId, 'phone_number' => $result['phone_number']];
    }
}
