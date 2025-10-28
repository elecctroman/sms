#!/usr/bin/env php
<?php
declare(strict_types=1);

use App\Core\Autoload;
use App\Core\Config;
use App\Repositories\OrderRepository;
use App\Services\Suppliers\NessaDemoAdapter;

require __DIR__ . '/../app/Core/Autoload.php';
Autoload::register(__DIR__ . '/..');
Config::load(require __DIR__ . '/../config/config.php');

$orders = new OrderRepository();
$adapter = new NessaDemoAdapter();

echo "[" . date('Y-m-d H:i:s') . "] Aktif siparişler kontrol ediliyor..." . PHP_EOL;
foreach ($orders->recentByUser(1) as $order) {
    if ($order['status'] !== 'active') {
        continue;
    }
    $status = $adapter->poll($order['external_order_id']);
    if ($status['status'] === 'completed') {
        echo "Sipariş #{$order['id']} tamamlandı" . PHP_EOL;
    }
}
