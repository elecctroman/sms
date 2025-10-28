<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\CSRF;
use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;
use App\Lib\Validation;
use App\Services\AuthService;

final class AuthController extends Controller
{
    private AuthService $auth;

    public function __construct(View $view, Session $session, CSRF $csrf)
    {
        parent::__construct($view, $session, $csrf);
        $this->auth = new AuthService($session);
    }

    public function login(): Response
    {
        return $this->render('site/login', [
            'csrf_token' => $this->csrf->token('login'),
        ]);
    }

    public function loginPost(array $request): Response
    {
        if (!$this->csrf->validate('login', $request['body']['_token'] ?? null)) {
            return new Response('Geçersiz CSRF token', 400);
        }

        $data = $request['body'];
        $errors = Validation::validate($data, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($errors !== []) {
            return $this->render('site/login', [
                'errors' => $errors,
                'old' => $data,
                'csrf_token' => $this->csrf->token('login'),
            ]);
        }

        if (!$this->auth->attempt((string) $data['email'], (string) $data['password'])) {
            $errorMessage = $this->auth->getLastError() ?? 'Giriş işlemi tamamlanamadı.';

            return $this->render('site/login', [
                'errors' => ['email' => $errorMessage],
                'old' => $data,
                'csrf_token' => $this->csrf->token('login'),
            ]);
        }

        return $this->redirect('/login/otp');
    }

    public function otp(): Response
    {
        return $this->render('site/login_otp', [
            'csrf_token' => $this->csrf->token('otp'),
        ]);
    }

    public function otpPost(array $request): Response
    {
        if (!$this->csrf->validate('otp', $request['body']['_token'] ?? null)) {
            return new Response('Geçersiz CSRF token', 400);
        }

        $code = (string) ($request['body']['code'] ?? '');
        if ($code === '' || !$this->auth->confirmOtp($code)) {
            return $this->render('site/login_otp', [
                'errors' => ['code' => 'Doğrulama kodu hatalı'],
                'csrf_token' => $this->csrf->token('otp'),
            ]);
        }

        return $this->redirect('/dashboard');
    }

    public function register(): Response
    {
        return $this->render('site/register', [
            'csrf_token' => $this->csrf->token('register'),
        ]);
    }

    public function registerPost(array $request): Response
    {
        if (!$this->csrf->validate('register', $request['body']['_token'] ?? null)) {
            return new Response('Geçersiz CSRF token', 400);
        }

        $data = $request['body'];
        $errors = Validation::validate($data, [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'phone' => ['required'],
            'terms' => ['required'],
        ]);

        if ($errors !== []) {
            return $this->render('site/register', [
                'errors' => $errors,
                'old' => $data,
                'csrf_token' => $this->csrf->token('register'),
            ]);
        }

        $this->auth->register([
            'name' => (string) $data['name'],
            'email' => (string) $data['email'],
            'password' => (string) $data['password'],
            'phone' => (string) $data['phone'],
        ]);

        return $this->redirect('/login');
    }

    public function logout(): Response
    {
        $this->auth->logout();

        return $this->redirect('/');
    }
}
