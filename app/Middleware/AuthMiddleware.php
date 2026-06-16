<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Http\Request;
use Core\Http\Middleware\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        if (! isset($_GET['auth'])) {
            http_response_code(401);

            return 'Unauthorized';
        }

        return $next($request);
    }
}
