<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ThemeController extends Controller
{
    public function index(Request $request, array $args = []): Response
    {
        $themes = [
            ['name' => 'Default Landing', 'slug' => 'landing/default'],
            ['name' => 'Default Admin', 'slug' => 'admin/default'],
        ];

        return $this->render('admin/theme/index.twig', ['themes' => $themes]);
    }
}
