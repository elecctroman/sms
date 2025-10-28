<?php

declare(strict_types=1);

use App\Adapters\Supplier\NessaDemoAdapter;
use App\Adapters\Supplier\ProviderXAdapter;
use App\Core\Application;
use App\Core\Config\ConfigRepository;
use App\Core\Container;
use App\Core\Database\ConnectionManager;
use App\Core\Localization\Translator;
use App\Core\Logger\Logger;
use App\Core\Logger\LoggerFactory;
use App\Core\Routing\Router;
use App\Core\Security\CsrfTokenManager;
use App\Core\Session\SessionManager;
use App\Repositories\CountryRepository;
use App\Repositories\OperatorRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ServicePriceRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\WalletTransactionRepository;
use App\Services\OrderPollingService;
use App\Services\StatisticsService;
use App\Services\SupplierSyncService;
use App\Support\Environment;

require_once __DIR__ . '/autoload.php';

$rootPath = dirname(__DIR__);
Environment::load($rootPath);

if (! function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}

$config = new ConfigRepository($rootPath . '/config');

$session = new SessionManager();
$session->start();

$translator = new Translator(
    $config->get('app.locale'),
    $config->get('app.fallback_locale'),
    $rootPath . '/resources/lang'
);

$loggerFactory = new LoggerFactory($rootPath . '/storage/logs');
$logger = $loggerFactory->make('app', 'app.log');

$container = new Container();
$container->instance(ConfigRepository::class, $config);
$container->instance(SessionManager::class, $session);
$container->instance(Translator::class, $translator);
$container->instance(LoggerFactory::class, $loggerFactory);
$container->instance(Logger::class, $logger);
$container->instance(ConnectionManager::class, new ConnectionManager($config));
$container->instance(CsrfTokenManager::class, new CsrfTokenManager(
    $config->get('app.csrf_secret'),
    $session
));

$router = new Router($container);
$container->instance(App\Core\Routing\Router::class, $router);

$container->bind(SupplierSyncService::class, static function (Container $container): SupplierSyncService {
    return new SupplierSyncService(
        [
            $container->make(NessaDemoAdapter::class),
            $container->make(ProviderXAdapter::class),
        ],
        $container->make(CountryRepository::class),
        $container->make(OperatorRepository::class),
        $container->make(ServiceRepository::class),
        $container->make(ServicePriceRepository::class)
    );
});

$container->bind(OrderPollingService::class, static function (Container $container): OrderPollingService {
    return new OrderPollingService(
        [
            $container->make(NessaDemoAdapter::class),
            $container->make(ProviderXAdapter::class),
        ],
        $container->make(OrderRepository::class)
    );
});

$container->bind(StatisticsService::class, static function (Container $container): StatisticsService {
    return new StatisticsService(
        $container->make(OrderRepository::class),
        $container->make(WalletTransactionRepository::class)
    );
});

return new Application(
    rootPath: $rootPath,
    container: $container,
    config: $config,
    router: $router,
    sessionManager: $session,
    translator: $translator,
    logger: $logger
);
