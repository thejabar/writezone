<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Authentication\Auth;
use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        if (! Auth::check()) {
            http_response_code(401);

            return 'Unauthorized';
        }

        return $next($request);
    }
}