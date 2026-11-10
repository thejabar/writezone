<?php

declare(strict_types=1);

namespace App\Intelligence\Remote;

final class PythonClassificationClientFactory
{
    /**
     * Build a client from one validated configuration snapshot.
     *
     * The optional transport and clock keep isolated dependency injection
     * available to tests. Production callers should omit the transport.
     *
     * @param null|array<string, mixed> $configuration
     */
    public static function create(
        ?array $configuration = null,
        ?HttpTransport $transport = null,
        ?callable $monotonicClock = null
    ): ?PythonClassificationClient {
        $configuration ??= require dirname(__DIR__, 3) . '/config/intelligence.php';
        $settings = PythonClassificationClientConfig::fromArray($configuration);

        if ($settings === null) {
            return null;
        }

        $destination = $settings->destination();
        $transport ??= new CurlHttpTransport($destination);

        return new PythonClassificationClient(
            $transport,
            $settings,
            $monotonicClock
        );
    }
}
