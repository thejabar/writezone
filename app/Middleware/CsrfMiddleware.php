<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request;
use Core\Security\Csrf;

final class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        if (
            in_array(
                $request->method(),
                ['POST', 'PUT', 'PATCH', 'DELETE'],
                true
            )
        ) {
            $token = $request->input('_token');

            if (! is_string($token) || $token === '') {
                $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            }

            if (! Csrf::validate($token)) {
                http_response_code(403);
                return 'CSRF validation failed.';
            }
        }

        return $next($request);
    }
}
