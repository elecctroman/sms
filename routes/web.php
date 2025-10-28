<?php

declare(strict_types=1);

use App\Controllers\AddFundsController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\LandingController;
use App\Controllers\OrderController;
use App\Controllers\RentNumberController;
use App\Controllers\SupportController;
use App\Controllers\Admin\ThemeController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\AdminMiddleware;

/** @var App\Core\Application $app */
$app = require __DIR__ . '/../bootstrap/app.php';
$router = $app->getContainer()->make(App\Core\Routing\Router::class);

$router->get('/', [LandingController::class, 'index'], 'landing.home');
$router->get('/faq', [LandingController::class, 'faq'], 'landing.faq');
$router->get('/blog', [LandingController::class, 'blog'], 'landing.blog');
$router->get('/announcements', [LandingController::class, 'announcements'], 'landing.announcements');

$router->get('/login', [AuthController::class, 'showLogin'], 'auth.login');
$router->get('/register', [AuthController::class, 'showRegister'], 'auth.register');
$router->get('/forgot-password', [AuthController::class, 'showForgotPassword'], 'auth.forgot');

$router->get('/dashboard', [DashboardController::class, 'index'], 'dashboard', [AuthMiddleware::class]);
$router->get('/orders', [OrderController::class, 'index'], 'orders.index', [AuthMiddleware::class]);
$router->get('/orders/new', [OrderController::class, 'create'], 'orders.create', [AuthMiddleware::class]);
$router->get('/rent-number', [RentNumberController::class, 'index'], 'rent.index', [AuthMiddleware::class]);
$router->get('/add-funds', [AddFundsController::class, 'index'], 'funds.index', [AuthMiddleware::class]);
$router->get('/support', [SupportController::class, 'index'], 'support.index', [AuthMiddleware::class]);
$router->get('/support/create', [SupportController::class, 'create'], 'support.create', [AuthMiddleware::class]);

$router->get('/admin/themes', [ThemeController::class, 'index'], 'admin.themes', [AuthMiddleware::class, AdminMiddleware::class]);

return $app;
