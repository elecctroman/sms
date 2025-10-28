<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Repositories\ServicePriceRepository;
use App\Repositories\ServiceRepository;

final class PricingController extends Controller
{
    private ServiceRepository $services;
    private ServicePriceRepository $prices;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->services = new ServiceRepository();
        $this->prices = new ServicePriceRepository();
    }

    public function index(): Response
    {
        return $this->render('admin/pricing_index', [
            'services' => $this->services->allEnabled(),
        ]);
    }
}
