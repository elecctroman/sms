<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Services\StatisticsService;

final class DashboardController extends Controller
{
    private StatisticsService $stats;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->stats = new StatisticsService();
    }

    public function index(): Response
    {
        return $this->render('admin/dashboard', [
            'daily' => $this->stats->revenueByDay(),
            'monthly' => $this->stats->revenueByMonth(),
        ]);
    }
}
