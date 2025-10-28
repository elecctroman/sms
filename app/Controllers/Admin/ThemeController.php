<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Http\Request;
use App\Http\Response;

class ThemeController extends Controller
{
    public function index(Request $request, array $args = []): Response
    {
        return $this->render('admin/theme/index');
    }
}
