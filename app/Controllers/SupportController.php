<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SupportController extends Controller
{
    public function index(Request $request, array $args = []): Response
    {
        $tickets = [
            ['id' => 1001, 'subject' => 'Sipariş gelmedi', 'status' => 'open', 'priority' => 'high'],
            ['id' => 1002, 'subject' => 'Fiyatlandırma sorusu', 'status' => 'waiting', 'priority' => 'normal'],
        ];

        return $this->render('admin/support/index.twig', ['tickets' => $tickets]);
    }

    public function create(Request $request, array $args = []): Response
    {
        return $this->render('admin/support/create.twig');
    }
}
