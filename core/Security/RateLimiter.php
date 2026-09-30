<?php
declare(strict_types=1);

namespace Core\Security;

final class RateLimiter
{
    public function __construct(
        private readonly string $directory,
    ) {
        if (! is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        }
    }

    public function hit(string $key, int $maxAttempts, int $decaySeconds): array
    {
        if ($maxAttempts < 1 || $decaySeconds < 1) {
            throw new \InvalidArgumentException('Invalid rate-limit configuration.');
        }

        $safeKey = hash('sha256', $key);
        $file = $this->directory . DIRECTORY_SEPARATOR . $safeKey . '.json';
        $handle = fopen($file, 'c+');

        if ($handle === false) {
            throw new \RuntimeException('Unable to open rate-limit storage.');
        }

        try {
            if (! flock($handle, LOCK_EX)) {
                throw new \RuntimeException('Unable to lock rate-limit storage.');
            }

            rewind($handle);
            $raw = stream_get_contents($handle);
            $state = is_string($raw) && $raw !== ''
                ? json_decode($raw, true)
                : null;

            $now = time();
            $windowStarted = is_array($state) ? (int) ($state['started_at'] ?? 0) : 0;
            $attempts = is_array($state) ? (int) ($state['attempts'] ?? 0) : 0;

            if ($windowStarted <= 0 || ($now - $windowStarted) >= $decaySeconds) {
                $windowStarted = $now;
                $attempts = 0;
            }

            $attempts++;
            $resetAt = $windowStarted + $decaySeconds;
            $allowed = $attempts <= $maxAttempts;

            $newState = json_encode([
                'started_at' => $windowStarted,
                'attempts' => $attempts,
            ], JSON_THROW_ON_ERROR);

            rewind($handle);
            ftruncate($handle, 0);
            fwrite($handle, $newState);
            fflush($handle);
            flock($handle, LOCK_UN);

            return [
                'allowed' => $allowed,
                'attempts' => $attempts,
                'remaining' => max(0, $maxAttempts - $attempts),
                'reset_at' => $resetAt,
                'retry_after' => max(1, $resetAt - $now),
            ];
        } finally {
            fclose($handle);
        }
    }
}
