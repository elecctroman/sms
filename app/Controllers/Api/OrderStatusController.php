<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Repositories\OrderRepository;

final class OrderStatusController extends Controller
{
    private OrderRepository $orders;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->orders = new OrderRepository();
    }

    public function show(array $request): Response
    {
        $id = (int) ($request['params']['id'] ?? 0);
        $row = $this->orders->findStatus($id);
        if ($row === null) {
            return $this->json(['error' => 'Sipariş bulunamadı'], 404);
        }

        return $this->json($row);
    }
}
