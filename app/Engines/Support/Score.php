<?php

declare(strict_types=1);

namespace App\Engines\Support;

class Score
{
    public function __construct(
        public readonly float $value = 0.0
    ) {
    }

    public function add(
        float $points
    ): self {

        return new self(
            $this->value + $points
        );

    }
}