#!/usr/bin/env php
<?php
declare(strict_types=1);

use App\Core\Autoload;
use App\Core\Config;
use App\Services\StatisticsService;

require __DIR__ . '/../app/Core/Autoload.php';
Autoload::register(__DIR__ . '/..');
Config::load(require __DIR__ . '/../config/config.php');

$stats = new StatisticsService();
echo '[' . date('Y-m-d H:i:s') . "] İstatistikler güncellendi" . PHP_EOL;
print_r($stats->revenueByDay());
