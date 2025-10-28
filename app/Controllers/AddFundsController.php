<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddFundsController extends Controller
{
    public function index(Request $request, array $args = []): Response
    {
        $methods = [
            ['name' => 'Shopier', 'fee' => 0.039, 'min' => 50],
            ['name' => 'iyzico', 'fee' => 0.029, 'min' => 100],
            ['name' => 'PayTR', 'fee' => 0.035, 'min' => 75],
        ];

        return $this->render('admin/funds/index.twig', ['methods' => $methods]);
    }
}
