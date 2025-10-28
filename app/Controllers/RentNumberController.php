<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RentNumberController extends Controller
{
    public function index(Request $request, array $args = []): Response
    {
        $rentalOptions = [
            ['period' => 'hourly', 'label' => 'Saatlik', 'price' => 2.99],
            ['period' => 'daily', 'label' => 'Günlük', 'price' => 9.99],
            ['period' => 'weekly', 'label' => 'Haftalık', 'price' => 49.99],
        ];

        return $this->render('admin/rent/index.twig', [
            'options' => $rentalOptions,
            'activeRental' => [
                'number' => '+905555555555',
                'ends_at' => date('Y-m-d H:i:s', strtotime('+6 hours')),
            ],
        ]);
    }
}
