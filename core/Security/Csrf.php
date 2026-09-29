<?php

declare(strict_types=1);

namespace Core\Security;

use Core\Session\Session;

final class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        $token = Session::get(self::SESSION_KEY);

        if (! is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            Session::put(self::SESSION_KEY, $token);
        }

        return $token;
    }

    public static function validate(?string $token): bool
    {
        if (! is_string($token) || $token === '') {
            return false;
        }

        $expected = Session::get(self::SESSION_KEY);

        if (! is_string($expected) || $expected === '') {
            return false;
        }

        return hash_equals($expected, $token);
    }
}
