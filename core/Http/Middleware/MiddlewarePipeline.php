<?php

declare(strict_types=1);

namespace Core\Http\Middleware;

use Core\Http\Request;

class MiddlewarePipeline
{
    public function __construct(
        private array $middlewares = []
    ) {}

    public function process(Request $request, callable $destination): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            fn ($next, $middleware) => fn ($request) => $middleware->handle($request, $next),
            $destination
        );

        return $pipeline($request);
    }
}
