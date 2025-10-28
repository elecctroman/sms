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
    private ?string $lastError = null;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->users = new UserRepository();
        $this->mailer = new SimpleMailer();
    }

    public function attempt(string $email, string $password): bool
    {
        $this->lastError = null;

        $user = $this->users->findByEmail($email);
        if ($user === null || !password_verify($password, (string) ($user['pass_hash'] ?? ''))) {
            $this->lastError = 'E-posta veya şifre hatalı.';

            return false;
        }

        if (($user['status'] ?? 'active') !== 'active') {
            $this->lastError = 'Hesabınız aktif değil. Lütfen destek ile iletişime geçin.';

            return false;
        }

        $otp = $this->generateOtp();
        $this->session->put('pending_2fa', ['user' => $user, 'otp' => $otp, 'expires' => time() + 300]);

        if (!$this->mailer->send($email, 'Giriş Doğrulama Kodu', '<p>Giriş doğrulama kodunuz: <strong>' . $otp . '</strong></p>')) {
            $this->lastError = $this->mailer->getLastError() ?? 'Doğrulama e-postası gönderilemedi. Lütfen birkaç dakika sonra tekrar deneyin veya SMTP ayarlarınızı kontrol edin.';
            $this->session->forget('pending_2fa');

            return false;
        }

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

    public function getLastError(): ?string
    {
        return $this->lastError;
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
