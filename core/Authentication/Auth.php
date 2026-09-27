<?php

declare(strict_types=1);

namespace Core\Authentication;

use Core\Session\Session;

final class Auth
{
    public static function login(int|string $userId): void
    {
        Session::regenerate(true);
        Session::put('user_id', $userId);
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function id(): int|string|null
    {
        return Session::get('user_id');
    }

    public static function user(): ?object
    {
        if (! self::check()) {
            return null;
        }

        return \App\Models\User::find(
            self::id()
        );
    }
}
