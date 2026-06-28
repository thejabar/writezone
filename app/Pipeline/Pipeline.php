<?php

declare(strict_types=1);

namespace App\Pipeline;

final class Pipeline
{
    /**
     * @var Stage[]
     */
    private array $stages = [];

    public function through(
        Stage $stage
    ): self {

        $this->stages[] = $stage;

        return $this;
    }

    public function process(
        mixed $payload
    ): mixed {

        foreach ($this->stages as $stage) {
            $payload = $stage->process($payload);
        }

        return $payload;
    }
}