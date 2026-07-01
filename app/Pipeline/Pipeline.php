<?php

declare(strict_types=1);

namespace App\Pipeline;

use App\Engines\Contracts\Processor;

final class Pipeline
{
    /**
     * @var Processor[]
     */
    private array $stages = [];

    public function through(
        Processor $stage
    ): self {

        $this->stages[] = $stage;

        return $this;
    }

    public function process(
        array $payload
    ): array {

        foreach ($this->stages as $stage) {
            $payload = $stage->process($payload);
        }

        return $payload;
    }
}