<?php

declare(strict_types=1);

namespace Core\Http;

class Response
{
    public static function send(string $content, int $status = 200): void
    {
        http_response_code($status);

        echo $content;
    }
}
