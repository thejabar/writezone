<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Core\Authentication\Auth;

class AuthController
{
    public function login(): string
    {
        $email = 'admin@writezone.org';
        $password = 'secret123';

        $user = User::where('email', $email);

        if (! $user) {
            return 'User not found.';
        }

        if (! password_verify($password, $user->password)) {
            return 'Invalid credentials.';
        }

        Auth::login($user->id);

        return "Logged in as {$user->username}";
    }

    public function logout(): string
    {
        Auth::logout();

        return 'Logged out';
    }
}