<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class LandingController extends Controller
{
    public function index(Request $request, array $args = []): Response
    {
        return $this->render('landing/home', [
            'hero' => [
                'title' => 'Anında SMS Onayı',
                'subtitle' => 'Global servisler için güvenilir sanal numara tedariki.',
            ],
            'testimonials' => [
                ['name' => 'Ayşe', 'role' => 'Ürün Yöneticisi', 'message' => 'SMS onay süreçlerimizi hızlandırdı.'],
                ['name' => 'John', 'role' => 'Growth Lead', 'message' => 'Dünya çapında numara kalitesi mükemmel.'],
            ],
        ]);
    }

    public function faq(Request $request, array $args = []): Response
    {
        return $this->render('landing/faq');
    }

    public function blog(Request $request, array $args = []): Response
    {
        return $this->render('landing/blog');
    }

    public function announcements(Request $request, array $args = []): Response
    {
        return $this->render('landing/announcements');
    }
}
