<?php
declare(strict_types=1);

use App\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Controllers\Admin\PricingController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\SuppliersController;
use App\Controllers\Admin\SystemController;
use App\Controllers\Admin\UsersController;
use App\Controllers\Api\OrderStatusController;
use App\Controllers\Site\AuthController;
use App\Controllers\Site\DashboardController;
use App\Controllers\Site\FundsController;
use App\Controllers\Site\HomeController;
use App\Controllers\Site\OrderController;
use App\Controllers\Site\RentController;
use App\Controllers\Site\SupportController;

$router->registerMiddleware('auth', static function (array $request) {
    $middleware = new App\Middlewares\AuthMiddleware();

    return $middleware($request);
});

$router->registerMiddleware('admin', static function (array $request) {
    $middleware = new App\Middlewares\AdminMiddleware();

    return $middleware($request);
});

$router->registerMiddleware('rate', static function (array $request) {
    $middleware = new App\Middlewares\RateLimiter();

    return $middleware($request);
});

// Landing
$router->add('GET', '/', [HomeController::class, 'index'], 'home');
$router->add('GET', '/faq', [HomeController::class, 'faq'], 'faq');
$router->add('GET', '/announcements', [HomeController::class, 'announcements'], 'announcements');
$router->add('GET', '/blog', [HomeController::class, 'blog'], 'blog');
$router->add('GET', '/blog/{slug}', [HomeController::class, 'blogDetail'], 'blog.show');

// Auth
$router->add('GET', '/login', [AuthController::class, 'login'], 'login');
$router->add('POST', '/login', [AuthController::class, 'loginPost'], 'login.post', ['rate']);
$router->add('GET', '/login/otp', [AuthController::class, 'otp'], 'login.otp');
$router->add('POST', '/login/otp', [AuthController::class, 'otpPost'], 'login.otp.post', ['rate']);
$router->add('GET', '/register', [AuthController::class, 'register'], 'register');
$router->add('POST', '/register', [AuthController::class, 'registerPost'], 'register.post');
$router->add('GET', '/logout', [AuthController::class, 'logout'], 'logout', ['auth']);

// Dashboard & account
$router->add('GET', '/dashboard', [DashboardController::class, 'index'], 'dashboard', ['auth']);
$router->add('GET', '/add-funds', [FundsController::class, 'index'], 'funds', ['auth']);
$router->add('GET', '/rent-number', [RentController::class, 'index'], 'rent', ['auth']);
$router->add('POST', '/rent-number', [RentController::class, 'index'], 'rent.post', ['auth']);
$router->add('GET', '/support', [SupportController::class, 'index'], 'support', ['auth']);
$router->add('POST', '/support', [SupportController::class, 'store'], 'support.store', ['auth']);

// Orders
$router->add('GET', '/orders', [OrderController::class, 'index'], 'orders.index', ['auth']);
$router->add('GET', '/orders/new', [OrderController::class, 'createForm'], 'orders.new', ['auth']);
$router->add('POST', '/orders', [OrderController::class, 'store'], 'orders.store', ['auth']);
$router->add('POST', '/orders/quote', [OrderController::class, 'quote'], 'orders.quote', ['auth']);

// API
$router->add('GET', '/api/orders/{id}', [OrderStatusController::class, 'show'], 'api.orders.show', ['auth']);

// Admin
$router->add('GET', '/admin', [AdminDashboardController::class, 'index'], 'admin.dashboard', ['auth', 'admin']);
$router->add('GET', '/admin/users', [UsersController::class, 'index'], 'admin.users', ['auth', 'admin']);
$router->add('GET', '/admin/suppliers', [SuppliersController::class, 'index'], 'admin.suppliers', ['auth', 'admin']);
$router->add('GET', '/admin/pricing', [PricingController::class, 'index'], 'admin.pricing', ['auth', 'admin']);
$router->add('GET', '/admin/settings', [SettingsController::class, 'index'], 'admin.settings', ['auth', 'admin']);
$router->add('POST', '/admin/settings', [SettingsController::class, 'update'], 'admin.settings.update', ['auth', 'admin']);
$router->add('GET', '/admin/system-check', [SystemController::class, 'check'], 'admin.system', ['auth', 'admin']);
