<?php

declare(strict_types=1);

namespace App\Core\Security;

use App\Core\Session\SessionManager;
use App\Http\Request;

class CsrfTokenManager
{
    public function __construct(
        private readonly string $secret,
        private readonly SessionManager $session
    ) {
    }

    public function token(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->session->put('_csrf_token', hash_hmac('sha256', $token, $this->secret));
        return $token;
    }

    public function validateRequest(Request $request, SessionManager $session): bool
    {
        if ($request->getMethod() !== 'POST') {
            return true;
        }

        $submitted = (string) $request->input('_token', '');
        $stored = (string) $session->get('_csrf_token', '');

        if ($submitted === '') {
            return false;
        }

        $calculated = hash_hmac('sha256', $submitted, $this->secret);

        return hash_equals($stored, $calculated);
    }
}
