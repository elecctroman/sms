<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Session;
use App\Lib\SimpleMailer;
use App\Repositories\UserRepository;

final class AuthService
{
    private Session $session;
    private UserRepository $users;
    private SimpleMailer $mailer;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->users = new UserRepository();
        $this->mailer = new SimpleMailer();
    }

    public function attempt(string $email, string $password): bool
    {
        $user = $this->users->findByEmail($email);
        if ($user === null || !password_verify($password, (string) $user['pass_hash'])) {
            return false;
        }

        if (($user['status'] ?? 'active') !== 'active') {
            return false;
        }

        $otp = $this->generateOtp();
        $this->session->put('pending_2fa', ['user' => $user, 'otp' => $otp, 'expires' => time() + 300]);
        $this->mailer->send($email, 'Giriş Doğrulama Kodu', '<p>Giriş doğrulama kodunuz: <strong>' . $otp . '</strong></p>');

        return true;
    }

    public function confirmOtp(string $code): bool
    {
        $pending = $this->session->get('pending_2fa');
        if ($pending === null || $pending['expires'] < time()) {
            return false;
        }

        if (!hash_equals($pending['otp'], $code)) {
            return false;
        }

        $user = $pending['user'];
        unset($pending['otp'], $pending['user']);
        $this->session->put('user', $user);
        $this->session->forget('pending_2fa');

        return true;
    }

    public function logout(): void
    {
        $this->session->forget('user');
        $this->session->forget('pending_2fa');
    }

    public function register(array $data): int
    {
        return $this->users->create($data);
    }

    private function generateOtp(): string
    {
        return (string) random_int(100000, 999999);
    }
}
