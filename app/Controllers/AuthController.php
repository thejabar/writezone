<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Authentication\Auth;

class AuthController
{
    public function login(): string
    {
        Auth::login(1);

        return 'Logged in as user #1';
    }

    public function logout(): string
    {
        Auth::logout();

        return 'Logged out';
    }
}
