<?php

declare(strict_types=1);

namespace App\Engines\Contracts;

interface Processor
{
    public function process(
        array $payload
    ): array;
}