<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function showLogin(Request $request, array $args = []): Response
    {
        return $this->render('landing/auth/login.twig');
    }

    public function showRegister(Request $request, array $args = []): Response
    {
        return $this->render('landing/auth/register.twig');
    }

    public function showForgotPassword(Request $request, array $args = []): Response
    {
        return $this->render('landing/auth/forgot-password.twig');
    }
}
