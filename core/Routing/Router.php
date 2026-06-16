<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Container\Container;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Middleware\MiddlewarePipeline;

class Router
{
    private array $routes = [];

    private array $middlewareAliases = [];

    private array $pendingMiddleware = [];

    public function __construct(
        private Container $container
    ) {}

    public function alias(string $name, string $middleware): void
    {
        $this->middlewareAliases[$name] = $middleware;
    }

    public function middleware(string|array $middleware): self
    {
        $this->pendingMiddleware = (array) $middleware;

        return $this;
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable|array $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function patch(string $path, callable|array $handler): void
    {
        $this->addRoute('PATCH', $path, $handler);
    }

    public function delete(string $path, callable|array $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute(
        string $method,
        string $path,
        callable|array $handler
    ): void {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $this->pendingMiddleware,
        ];

        $this->pendingMiddleware = [];
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = trim($request->uri(), '/');

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route => $config) {

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

            $handler = $config['handler'];

            $middlewares = array_map(
    function (string $name) {

        if (! isset($this->middlewareAliases[$name])) {
            throw new \Exception(
                "Middleware alias '{$name}' is not registered."
            );
        }

        return $this->container->resolve(
            $this->middlewareAliases[$name]
        );
    },
    $config['middleware']
);

            $pipeline = new MiddlewarePipeline($middlewares);

            $response = $pipeline->process(
                $request,
                function () use ($handler, $matches) {

                    if (is_array($handler)) {
                        [$controller, $action] = $handler;

                        $instance = $this->container->resolve($controller);

                        return $instance->$action(...$matches);
                    }

                    return $handler(...$matches);
                }
            );

            Response::send((string) $response);

            return;
        }

        Response::send('404 Not Found', 404);
    }
}