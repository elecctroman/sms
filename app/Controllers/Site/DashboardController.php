<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Repositories\FavoritesRepository;
use App\Repositories\OrderRepository;
use App\Repositories\WalletRepository;
use App\Services\StatisticsService;

final class DashboardController extends Controller
{
    private WalletRepository $wallets;
    private OrderRepository $orders;
    private FavoritesRepository $favorites;
    private StatisticsService $stats;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->wallets = new WalletRepository();
        $this->orders = new OrderRepository();
        $this->favorites = new FavoritesRepository();
        $this->stats = new StatisticsService();
    }

    public function index(): Response
    {
        $user = $this->session->get('user');
        $wallet = $this->wallets->findByUser((int) $user['id']);
        $recentOrders = $this->orders->recentByUser((int) $user['id']);
        $favorites = $this->favorites->allForUser((int) $user['id']);

        return $this->render('site/dashboard', [
            'wallet' => $wallet,
            'recentOrders' => $recentOrders,
            'favorites' => $favorites,
            'dailyRevenue' => $this->stats->revenueByDay(),
        ]);
    }
}
