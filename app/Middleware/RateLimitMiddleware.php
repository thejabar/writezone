<?php
declare(strict_types=1);

namespace App\Middleware;

use Core\Authentication\Auth;
use Core\Http\Request;
use Core\Http\Response;
use Core\Security\RateLimiter;

final class RateLimitMiddleware
{
    public function handle(Request $request, callable $next): mixed
    {
        $config = require BASE_PATH . '/config/rate_limit.php';
        $profile = $this->profile($request);
        $policy = $config[$profile] ?? null;

        if (! is_array($policy)) {
            return Response::send('Rate-limit configuration not found.', 500);
        }

        $identities = $this->identities(
            (string) ($policy['identity'] ?? 'ip')
        );

        $limiter = new RateLimiter((string) $config['directory']);

        header('X-RateLimit-Limit: ' . $policy['max_attempts']);
        $remaining = (int) $policy['max_attempts'];

        foreach ($identities as $identity) {
            $key = $profile . ':' . $identity;

            $result = $limiter->hit(
                $key,
                (int) $policy['max_attempts'],
                (int) $policy['decay_seconds']
            );
            $remaining = min($remaining, (int) $result['remaining']);

            if (! $result['allowed']) {
                header('X-RateLimit-Remaining: 0');
                header('Retry-After: ' . $result['retry_after']);

                return Response::send('Too many requests.', 429);
            }
        }

        header('X-RateLimit-Remaining: ' . $remaining);

        return $next($request);
    }

    private function profile(Request $request): string
    {
        $path = rtrim($request->uri(), '/');

        if ($path === '') {
            $path = '/';
        }

        return match ($path) {
            '/login' => 'login',
            '/register' => 'register',
            '/lab', '/lab/analyze' => 'lab',
            default => 'auth_action',
        };
    }

    private function identities(string $type): array
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        if ($type === 'user_and_ip') {
            $identities = [
                'ip:' . $ip,
            ];

            if (Auth::check()) {
                $identities[] = 'user:' . ((string) Auth::id());
            }

            return $identities;
        }

        return [
            'ip:' . $ip,
        ];
    }
}
