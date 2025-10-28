<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Lib\Validation;
use App\Repositories\CountryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ServiceRepository;
use App\Services\PricingService;
use App\Services\SmsService;

final class OrderController extends Controller
{
    private ServiceRepository $services;
    private CountryRepository $countries;
    private PricingService $pricing;
    private SmsService $smsService;
    private OrderRepository $orders;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->services = new ServiceRepository();
        $this->countries = new CountryRepository();
        $this->pricing = new PricingService();
        $this->smsService = new SmsService();
        $this->orders = new OrderRepository();
    }

    public function index(): Response
    {
        $user = $this->session->get('user');
        $orders = $this->orders->recentByUser((int) $user['id']);

        return $this->render('site/orders_index', [
            'orders' => $orders,
            'csrf_token' => $this->csrf->token('order_cancel'),
        ]);
    }

    public function createForm(): Response
    {
        return $this->render('site/orders_new', [
            'services' => $this->services->allEnabled(),
            'countries' => $this->countries->enabled(),
            'csrf_token' => $this->csrf->token('order_create'),
        ]);
    }

    public function quote(array $request): Response
    {
        $data = $request['body'];
        $quote = $this->pricing->quote((int) $data['service_id'], (int) $data['country_id'], isset($data['operator_id']) ? (int) $data['operator_id'] : null);
        if ($quote === null) {
            return $this->json(['error' => 'Uygun fiyat bulunamadı'], 404);
        }

        return $this->json(['quote' => $quote]);
    }

    public function store(array $request): Response
    {
        if (!$this->csrf->validate('order_create', $request['body']['_token'] ?? null)) {
            return new Response('Geçersiz CSRF token', 400);
        }

        $data = $request['body'];
        $errors = Validation::validate($data, [
            'service_id' => ['required'],
            'country_id' => ['required'],
            'supplier' => ['required'],
        ]);

        if ($errors !== []) {
            return $this->render('site/orders_new', [
                'services' => $this->services->allEnabled(),
                'countries' => $this->countries->enabled(),
                'errors' => $errors,
                'csrf_token' => $this->csrf->token('order_create'),
            ]);
        }

        $user = $this->session->get('user');
        try {
            $order = $this->smsService->placeOrder([
                'user_id' => (int) $user['id'],
                'service_id' => (int) $data['service_id'],
                'country_id' => (int) $data['country_id'],
                'operator_id' => isset($data['operator_id']) ? (int) $data['operator_id'] : null,
                'supplier' => (string) $data['supplier'],
            ]);

            return $this->render('site/orders_success', ['order' => $order]);
        } catch (\Throwable $exception) {
            $this->session->put('flash', ['danger' => 'Sipariş oluşturulamadı: ' . $exception->getMessage()]);

            return $this->redirect('/orders/new');
        }
    }
}
