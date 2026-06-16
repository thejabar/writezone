<?php

declare(strict_types=1);

namespace Core\Authentication;

use Core\Session\Session;

class Auth
{
    public static function login(int|string $userId): void
    {
        Session::put('user_id', $userId);
    }

    public static function logout(): void
    {
        Session::forget('user_id');
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function id(): int|string|null
    {
        return Session::get('user_id');
    }
}
