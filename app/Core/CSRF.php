<?php
declare(strict_types=1);

namespace App\Core;

final class CSRF
{
    private Session $session;
    private int $lifetime;

    public function __construct(Session $session, int $lifetime)
    {
        $this->session = $session;
        $this->lifetime = $lifetime;
    }

    public function token(string $form): string
    {
        $tokens = $this->session->get('_csrf_tokens', []);
        $now = time();
        $token = bin2hex(random_bytes(32));
        $tokens[$form] = ['value' => $token, 'expires' => $now + $this->lifetime];
        $this->session->put('_csrf_tokens', $tokens);

        return $token;
    }

    public function validate(string $form, ?string $value): bool
    {
        $tokens = $this->session->get('_csrf_tokens', []);
        if (!isset($tokens[$form])) {
            return false;
        }

        $token = $tokens[$form];
        unset($tokens[$form]);
        $this->session->put('_csrf_tokens', $tokens);

        if ($token['expires'] < time()) {
            return false;
        }

        return hash_equals($token['value'], (string) $value);
    }
}
