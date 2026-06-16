<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Core\Authentication\Auth;
use Core\Http\Request;

class AuthController
{
    public function showLogin(Request $request): string
    {
        return view('auth.login');
    }

    public function login(Request $request): string
    {
        $user = User::where(
            'email',
            $request->input('email')
        );

        if (! $user) {
            return 'User not found.';
        }

        if (! password_verify(
            $request->input('password'),
            $user->password
        )) {
            return 'Invalid credentials.';
        }

        Auth::login($user->id);

        return "Logged in as {$user->username}";
    }

    public function showRegister(Request $request): string
    {
        return view('auth.register');
    }

    public function register(Request $request): string
    {
        $id = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => password_hash(
                $request->input('password'),
                PASSWORD_DEFAULT
            ),
        ]);

        Auth::login($id);

        return 'Registration successful.';
    }

    public function logout(Request $request): string
    {
        Auth::logout();

        return 'Logged out';
    }
}