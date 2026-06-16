<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Container\Container;
use Core\Http\Request;
use Core\Http\Response;

class Router
{
    private array $routes = [];

    public function __construct(
        private Container $container
    ) {}

    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = $request->uri();

        $handler = $this->routes[$method][$uri] ?? null;

        if (! $handler) {
            Response::send('404 Not Found', 404);
            return;
        }

        if (is_array($handler)) {
            [$controller, $action] = $handler;

            $instance = $this->container->resolve($controller);

            Response::send($instance->$action());

            return;
        }

        Response::send($handler());
    }
}