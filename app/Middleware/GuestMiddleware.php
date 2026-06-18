<?php
declare(strict_types=1);
namespace App\Middleware;
use Core\Authentication\Auth;
use Core\Http\Request;
use Core\Http\Response;
class GuestMiddleware
{
    public function handle(
        Request $request,
        callable $next
    ): mixed {
        if (Auth::check()) {
            Response::redirect('/dashboard');
        }
        return $next($request);
    }
}
