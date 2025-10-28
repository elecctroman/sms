<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class AuthController extends Controller
{
    public function showLogin(Request $request, array $args = []): Response
    {
        return $this->render('landing/auth/login');
    }

    public function showRegister(Request $request, array $args = []): Response
    {
        return $this->render('landing/auth/register');
    }

    public function showForgotPassword(Request $request, array $args = []): Response
    {
        return $this->render('landing/auth/forgot-password');
    }
}
