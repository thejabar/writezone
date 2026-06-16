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
    public function post(string $path, callable|array $handler): void
{
    $this->routes['POST'][$path] = $handler;
}

public function put(string $path, callable|array $handler): void
{
    $this->routes['PUT'][$path] = $handler;
}

public function patch(string $path, callable|array $handler): void
{
    $this->routes['PATCH'][$path] = $handler;
}

public function delete(string $path, callable|array $handler): void
{
    $this->routes['DELETE'][$path] = $handler;
}

    public function dispatch(Request $request): void
{
    $method = $request->method();
    $uri = trim($request->uri(), '/');

    $routes = $this->routes[$method] ?? [];

    foreach ($routes as $route => $handler) {

        $pattern = preg_replace(
            '#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#',
            '([^/]+)',
            trim($route, '/')
        );

        $pattern = "#^{$pattern}$#";

        if (! preg_match($pattern, $uri, $matches)) {
            continue;
        }

        array_shift($matches);

        if (is_array($handler)) {
            [$controller, $action] = $handler;

            $instance = $this->container->resolve($controller);

            Response::send(
                $instance->$action(...$matches)
            );

            return;
        }

        Response::send(
            $handler(...$matches)
        );

        return;
    }

    Response::send('404 Not Found', 404);
}
}