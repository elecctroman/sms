<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class AddFundsController extends Controller
{
    public function index(Request $request, array $args = []): Response
    {
        return $this->render('admin/funds/index');
    }
}
