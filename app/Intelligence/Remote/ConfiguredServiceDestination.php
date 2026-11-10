<?php

declare(strict_types=1);

namespace App\Intelligence\Remote;

final readonly class ConfiguredServiceDestination
{
    private function __construct(private string $baseUrl)
    {
    }

    public static function fromValidatedConfiguration(
        PythonClassificationClientConfig $configuration
    ): self {
        return new self($configuration->url);
    }

    public function urlForPath(string $path): ?string
    {
        if (! self::isValidRelativePath($path)) {
            return null;
        }

        return $this->baseUrl . $path;
    }

    private static function isValidRelativePath(string $path): bool
    {
        if (
            $path === ''
            || ! str_starts_with($path, '/')
            || str_starts_with($path, '//')
            || preg_match('/[\x00-\x20\x7F\\?#%]/', $path) === 1
            || preg_match('/\A\/[A-Za-z0-9._~!$&\'*+,;=:@\/-]+\z/', $path) !== 1
        ) {
            return false;
        }

        $parts = parse_url($path);

        if (
            ! is_array($parts)
            || isset(
                $parts['scheme'],
                $parts['host'],
                $parts['user'],
                $parts['pass'],
                $parts['port'],
                $parts['query'],
                $parts['fragment']
            )
            || ($parts['path'] ?? null) !== $path
        ) {
            return false;
        }

        foreach (explode('/', substr($path, 1)) as $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..') {
                return false;
            }
        }

        return true;
    }
}
