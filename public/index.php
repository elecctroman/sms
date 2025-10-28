<?php

declare(strict_types=1);

if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $fullPath = __DIR__ . $path;
    if ($path !== '/' && file_exists($fullPath)) {
        return false;
    }
}

$app = require __DIR__ . '/../routes/web.php';
$app->run();
