<?php

declare(strict_types=1);

namespace Core\Container;

class Container
{
    private array $bindings = [];

    public function bind(string $abstract, callable $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    public function resolve(string $abstract): mixed
    {
        if (! isset($this->bindings[$abstract])) {
            throw new \Exception("Class {$abstract} is not bound.");
        }

        return $this->bindings[$abstract]($this);
    }
}
