<?php

declare(strict_types=1);

namespace App\Engines\Support;

use App\Engines\Contracts\Processor;

class Pipeline
{
    /**
     * @var Processor[]
     */
    private array $processors = [];

    public function through(
        Processor ...$processors
    ): self {

        $this->processors = $processors;

        return $this;

    }

    public function process(
        array $payload
    ): array {

        foreach (
            $this->processors
            as $processor
        ) {

            $payload = $processor
                ->process($payload);

        }

        return $payload;

    }
}