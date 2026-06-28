<?php

declare(strict_types=1);

namespace App\Pipeline;

interface Stage
{
    public function process(
        mixed $payload
    ): mixed;
}