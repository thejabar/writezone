<?php

declare(strict_types=1);

namespace Core\Http;

final class Response
{
    /**
     * Send a plain text or HTML response.
     */
    public static function send(
        string $content,
        int $status = 200
    ): void {

        http_response_code($status);

        echo $content;
    }

    /**
     * Send a JSON response.
     *
     * @param array<string,mixed> $data
     */
    public static function json(
        array $data,
        int $status = 200
    ): never {

        http_response_code($status);

        header(
            'Content-Type: application/json; charset=utf-8'
        );

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
            | JSON_UNESCAPED_SLASHES
        );

        exit;
    }

    /**
     * Redirect to another URL.
     */
    public static function redirect(
        string $url
    ): never {

        header(
            "Location: {$url}"
        );

        exit;
    }
}