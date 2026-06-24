<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\User;
use Core\Authentication\Auth;
use Core\Http\Request;
use Core\Http\Response;
class AuthController
{
    public function showLogin(
        Request $request
    ): string {
        return view('auth.login');
    }
    public function login(
        Request $request
    ): string {
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
        Auth::login(
            (int) $user->id
        );
        flash(
            'success',
            'Welcome back!'
        );
        Response::redirect(
            '/dashboard'
        );
        return '';
    }
    public function showRegister(
        Request $request
    ): string {
        return view(
            'auth.register'
        );
    }
    public function register(
        Request $request
    ): string {
        $username = trim(
            $request->input('username')
        );
        $handle = strtolower(
            preg_replace(
                '/[^a-zA-Z0-9_]/',
                '',
                $username
            )
        );
        $id = User::create([
            'username' => $username,
            'handle'   => $handle,
            'email'    => trim(
                $request->input('email')
            ),
            'password' => password_hash(
                $request->input('password'),
                PASSWORD_DEFAULT
            ),
        ]);
        Auth::login(
            (int) $id
        );
        flash(
            'success',
            'Account created successfully.'
        );
        Response::redirect(
            '/dashboard'
        );
        return '';
    }
    public function logout(
        Request $request
    ): string {
        Auth::logout();
        flash(
            'success',
            'You have been signed out.'
        );
        Response::redirect(
            '/'
        );
        return '';
    }
}