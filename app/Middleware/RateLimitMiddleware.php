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

        $identity = $this->identity((string) ($policy['identity'] ?? 'ip'));
        $key = $profile . ':' . $identity;

        $limiter = new RateLimiter((string) $config['directory']);
        $result = $limiter->hit(
            $key,
            (int) $policy['max_attempts'],
            (int) $policy['decay_seconds']
        );

        header('X-RateLimit-Limit: ' . $policy['max_attempts']);

        if (! $result['allowed']) {
            header('X-RateLimit-Remaining: 0');
            header('Retry-After: ' . $result['retry_after']);
            return Response::send('Too many requests.', 429);
        }

        header('X-RateLimit-Remaining: ' . $result['remaining']);

        return $next($request);
    }

    private function profile(Request $request): string
    {
        return match ($request->uri()) {
            '/login' => 'login',
            '/register' => 'register',
            '/lab', '/lab/analyze' => 'lab',
            default => 'auth_action',
        };
    }

    private function identity(string $type): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        if ($type === 'user_ip') {
            $userId = Auth::id();
            return 'user:' . ((string) ($userId ?? 0)) . ':ip:' . $ip;
        }

        return 'ip:' . $ip;
    }
}
