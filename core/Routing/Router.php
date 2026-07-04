<?php

declare(strict_types=1);

namespace Core\Routing;

use Exception;
use Core\Container\Container;
use Core\Http\Middleware\MiddlewarePipeline;
use Core\Http\Request;
use Core\Http\Response;

final class Router
{
    /**
     * Registered application routes.
     *
     * @var array<string,array<string,array{
     *     handler: callable|array,
     *     middleware: array<int,string>
     * }>>
     */
    private array $routes = [];

    /**
     * Registered middleware aliases.
     *
     * @var array<string,string>
     */
    private array $middlewareAliases = [];

    /**
     * Middleware awaiting registration
     * on the next route.
     *
     * @var string[]
     */
    private array $pendingMiddleware = [];

    public function __construct(
        private readonly Container $container
    ) {
    }

    /**
     * Register a middleware alias.
     */
    public function alias(
        string $name,
        string $middleware
    ): void {
        $this->middlewareAliases[$name] = $middleware;
    }

    /**
     * Attach middleware to the next route.
     */
    public function middleware(
        string|array $middleware
    ): self {

        $this->pendingMiddleware = (array) $middleware;

        return $this;
    }

    public function get(
        string $path,
        callable|array $handler
    ): void {
        $this->addRoute(
            'GET',
            $path,
            $handler
        );
    }

    public function post(
        string $path,
        callable|array $handler
    ): void {
        $this->addRoute(
            'POST',
            $path,
            $handler
        );
    }

    public function put(
        string $path,
        callable|array $handler
    ): void {
        $this->addRoute(
            'PUT',
            $path,
            $handler
        );
    }

    public function patch(
        string $path,
        callable|array $handler
    ): void {
        $this->addRoute(
            'PATCH',
            $path,
            $handler
        );
    }

    public function delete(
        string $path,
        callable|array $handler
    ): void {
        $this->addRoute(
            'DELETE',
            $path,
            $handler
        );
    }

    /**
     * Register a route.
     */
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

    /**
     * Dispatch the current request.
     */
    public function dispatch(
        Request $request
    ): void {

        $method = $request->method();

        $uri = trim(
            $request->uri(),
            '/'
        );

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route => $config) {

            $pattern = preg_replace(
                '#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#',
                '([^/]+)',
                trim($route, '/')
            );

            $pattern = "#^{$pattern}$#";

            if (
                ! preg_match(
                    $pattern,
                    $uri,
                    $matches
                )
            ) {
                continue;
            }

            array_shift($matches);

            $handler = $config['handler'];

            $middlewares = array_map(

                function (
                    string $name
                ) {

                    if (
                        ! isset(
                            $this->middlewareAliases[$name]
                        )
                    ) {

                        throw new Exception(
                            "Middleware alias '{$name}' is not registered."
                        );

                    }

                    return $this->container->resolve(
                        $this->middlewareAliases[$name]
                    );

                },

                $config['middleware']

            );

            $pipeline = new MiddlewarePipeline(
                $middlewares
            );

            $response = $pipeline->process(

                $request,

                function () use (
                    $handler,
                    $matches,
                    $request
                ) {

                    if (
                        is_array($handler)
                    ) {

                        [$controller, $action] = $handler;

                        $instance = $this->container->resolve(
                            $controller
                        );

                        return $instance->$action(
                            $request,
                            ...$matches
                        );

                    }

                    return $handler(
                        $request,
                        ...$matches
                    );

                }

            );

            Response::send(
                (string) $response
            );

            return;
        }

        Response::send(
            '404 Not Found',
            404
        );
    }
}