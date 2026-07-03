<?php

declare(strict_types=1);

namespace App\Engines;

abstract class Engine
{
    /**
     * Execute the engine.
     *
     * @param array $payload
     * @return mixed
     */
    abstract public function execute(
        array $payload = []
    ): mixed;
}