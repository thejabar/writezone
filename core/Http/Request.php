<?php

declare(strict_types=1);

namespace Core\Http;

class Request
{
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function uri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        return parse_url($uri, PHP_URL_PATH);
    }
}
