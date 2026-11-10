<?php

declare(strict_types=1);

namespace App\Intelligence\Remote;

final class CurlHttpTransport implements HttpTransport
{
    private const MAX_RESPONSE_BYTES = 65536;
    private const MAX_RESPONSE_HEADER_BYTES = 16384;

    public function __construct(
        private readonly ConfiguredServiceDestination $destination
    ) {
    }

    public function send(
        string $path,
        array $headers,
        string $body,
        int $connectTimeoutMs,
        int $timeoutMs
    ): HttpTransportResponse {
        $url = $this->destination->urlForPath($path);

        if ($url === null) {
            return HttpTransportResponse::failure('invalid_transport_request');
        }

        if (! function_exists('curl_init')) {
            return HttpTransportResponse::failure('transport_unavailable');
        }

        $headerLines = [];

        foreach ($headers as $name => $value) {
            if (
                ! is_string($name)
                || ! is_string($value)
                || preg_match('/[\r\n]/', $name . $value) === 1
            ) {
                return HttpTransportResponse::failure('invalid_transport_request');
            }

            $headerLines[] = $name . ': ' . $value;
        }

        $handle = curl_init($url);

        if ($handle === false) {
            return HttpTransportResponse::failure('transport_failure');
        }

        $responseBody = '';
        $responseTooLarge = false;
        $responseHeaders = [];
        $responseHeaderBytes = 0;
        $responseHeaderFailure = null;
        $options = [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => $headerLines,
            CURLOPT_CONNECTTIMEOUT_MS => max(1, $connectTimeoutMs),
            CURLOPT_TIMEOUT_MS => max(1, $timeoutMs),
            CURLOPT_NOSIGNAL => true,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_WRITEFUNCTION => static function (
                $curl,
                string $chunk
            ) use (&$responseBody, &$responseTooLarge): int {
                if (strlen($responseBody) + strlen($chunk) > self::MAX_RESPONSE_BYTES) {
                    $responseTooLarge = true;

                    return 0;
                }

                $responseBody .= $chunk;

                return strlen($chunk);
            },
            CURLOPT_HEADERFUNCTION => static function (
                $curl,
                string $line
            ) use (&$responseHeaders, &$responseHeaderBytes, &$responseHeaderFailure): int {
                $lineLength = strlen($line);
                $responseHeaderBytes += $lineLength;

                if ($responseHeaderBytes > self::MAX_RESPONSE_HEADER_BYTES) {
                    $responseHeaderFailure = 'response_headers_too_large';

                    return 0;
                }

                $trimmedLine = rtrim($line, "\r\n");

                if ($trimmedLine === '') {
                    return $lineLength;
                }

                if (preg_match('/\AHTTP\/\d(?:\.\d)?\s+\d{3}(?:\s|\z)/i', $trimmedLine) === 1) {
                    $responseHeaders = [];

                    return $lineLength;
                }

                $separator = strpos($trimmedLine, ':');

                if ($separator === false) {
                    $responseHeaderFailure = 'invalid_response_headers';

                    return 0;
                }

                $name = substr($trimmedLine, 0, $separator);
                $value = trim(substr($trimmedLine, $separator + 1), " \t");

                if (
                    preg_match('/\A[a-z0-9!#$%&\'*+.^_`|~-]+\z/i', $name) !== 1
                    || preg_match('/[\x00-\x08\x0A-\x1F\x7F]/', $value) === 1
                ) {
                    $responseHeaderFailure = 'invalid_response_headers';

                    return 0;
                }

                $responseHeaders[strtolower($name)][] = $value;

                return $lineLength;
            },
        ];

        $options = array_replace($options, self::securityOptions());

        if (! curl_setopt_array($handle, $options)) {
            curl_close($handle);

            return HttpTransportResponse::failure('transport_failure');
        }

        $result = curl_exec($handle);
        $curlError = curl_errno($handle);
        $statusCode = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        curl_close($handle);

        if ($responseTooLarge) {
            return HttpTransportResponse::failure('response_too_large');
        }

        if ($responseHeaderFailure !== null) {
            return HttpTransportResponse::failure($responseHeaderFailure);
        }

        if ($result === false) {
            return HttpTransportResponse::failure(
                $curlError === CURLE_OPERATION_TIMEDOUT
                    ? 'transport_timeout'
                    : 'transport_failure'
            );
        }

        return new HttpTransportResponse($statusCode, $responseBody, null, $responseHeaders);
    }

    /** @return array<int, mixed> */
    private static function securityOptions(): array
    {
        $options = [
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_PROXY => '',
        ];

        if (defined('CURLOPT_PROTOCOLS_STR')) {
            $options[constant('CURLOPT_PROTOCOLS_STR')] = 'http,https';
        } else {
            $options[CURLOPT_PROTOCOLS] = CURLPROTO_HTTP | CURLPROTO_HTTPS;
        }

        return $options;
    }
}
