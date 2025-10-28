<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LandingController extends Controller
{
    public function index(Request $request, array $args = []): Response
    {
        return $this->render('landing/home.twig', [
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
        return $this->render('landing/faq.twig');
    }

    public function blog(Request $request, array $args = []): Response
    {
        return $this->render('landing/blog.twig');
    }

    public function announcements(Request $request, array $args = []): Response
    {
        return $this->render('landing/announcements.twig');
    }
}
