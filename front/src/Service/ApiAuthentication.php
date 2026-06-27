<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class ApiAuthentication
{
    public const SESSION_TOKEN = 'token';

    public const SESSION_EMAIL = 'user_email';

    public function getToken(SessionInterface $session): ?string
    {
        $token = $session->get(self::SESSION_TOKEN);

        return is_string($token) && $token !== '' ? $token : null;
    }

    public function getEmail(SessionInterface $session): ?string
    {
        $email = $session->get(self::SESSION_EMAIL);

        return is_string($email) && $email !== '' ? $email : null;
    }

    public function store(SessionInterface $session, string $token, string $email): void
    {
        $session->set(self::SESSION_TOKEN, $token);
        $session->set(self::SESSION_EMAIL, $email);
    }

    public function clear(SessionInterface $session): void
    {
        $session->remove(self::SESSION_TOKEN);
        $session->remove(self::SESSION_EMAIL);
    }

    public function isAuthenticated(SessionInterface $session): bool
    {
        return null !== $this->getToken($session);
    }

    public function getTokenFromRequest(Request $request): ?string
    {
        return $this->getToken($request->getSession());
    }
}
