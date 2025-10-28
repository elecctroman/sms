<?php
declare(strict_types=1);

use App\Core\Autoload;
use App\Core\Config;
use App\Core\Router;
use App\Core\Security;

require __DIR__ . '/../app/Core/Autoload.php';

Autoload::register(__DIR__ . '/..');

$config = require __DIR__ . '/../config/config.php';
Config::load($config);

Security::enforceHttps((bool) ($config['app']['force_https'] ?? true));

$router = new Router();
require __DIR__ . '/../app/routes.php';

$response = $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
$response->send();
