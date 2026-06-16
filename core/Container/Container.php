<?php

declare(strict_types=1);

namespace Core\Container;

use ReflectionClass;
use ReflectionException;

class Container
{
    private array $instances = [];

    /**
     * @throws ReflectionException
     */
    public function resolve(string $class): object
    {
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        $reflection = new ReflectionClass($class);

        if (! $reflection->isInstantiable()) {
            throw new \Exception("Class {$class} is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if (! $constructor) {
            return new $class();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if (! $type || $type->isBuiltin()) {
                throw new \Exception(
                    "Cannot resolve parameter \${$parameter->getName()} in {$class}"
                );
            }

            $dependencies[] = $this->resolve($type->getName());
        }

        $instance = $reflection->newInstanceArgs($dependencies);

        $this->instances[$class] = $instance;

        return $instance;
    }
}