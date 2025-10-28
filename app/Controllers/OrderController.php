<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class OrderController extends Controller
{
    public function index(Request $request, array $args = []): Response
    {
        $orders = [
            ['id' => 1, 'service' => 'WhatsApp', 'status' => 'completed', 'code' => '123456', 'created_at' => '2023-10-01'],
            ['id' => 2, 'service' => 'Instagram', 'status' => 'active', 'code' => null, 'created_at' => '2023-10-04'],
        ];

        return $this->render('admin/orders/index', ['orders' => $orders]);
    }

    public function create(Request $request, array $args = []): Response
    {
        $services = [
            ['name' => 'WhatsApp', 'slug' => 'whatsapp', 'icon' => 'bi-whatsapp', 'color' => '#25D366'],
            ['name' => 'Instagram', 'slug' => 'instagram', 'icon' => 'bi-instagram', 'color' => '#E1306C'],
            ['name' => 'Telegram', 'slug' => 'telegram', 'icon' => 'bi-telegram', 'color' => '#26A5E4'],
        ];

        return $this->render('admin/orders/new', ['services' => $services]);
    }
}
