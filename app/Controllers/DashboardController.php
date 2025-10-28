<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function index(Request $request, array $args = []): Response
    {
        $stats = [
            'balance' => 150.45,
            'spent' => 1020.78,
            'orders' => 120,
        ];

        $favoriteServices = [
            ['name' => 'WhatsApp', 'slug' => 'whatsapp', 'icon' => 'bi-whatsapp', 'color' => '#25D366'],
            ['name' => 'Instagram', 'slug' => 'instagram', 'icon' => 'bi-instagram', 'color' => '#E1306C'],
        ];

        return $this->render('admin/dashboard.twig', [
            'stats' => $stats,
            'favoriteServices' => $favoriteServices,
        ]);
    }
}
