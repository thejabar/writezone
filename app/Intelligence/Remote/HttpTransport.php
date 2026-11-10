<?php

declare(strict_types=1);

namespace App\Intelligence\Remote;

interface HttpTransport
{
    /**
     * @param array<string, string> $headers
     */
    public function send(
        string $path,
        array $headers,
        string $body,
        int $connectTimeoutMs,
        int $timeoutMs
    ): HttpTransportResponse;
}
