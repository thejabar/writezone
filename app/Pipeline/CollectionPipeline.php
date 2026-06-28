<?php

declare(strict_types=1);

namespace App\Pipeline;

final class CollectionPipeline
{
    public function __construct(
        private readonly Pipeline $pipeline
    ) {
    }

    public function process(
        array $items,
        callable $payloadFactory
    ): array {

        $results = [];

        foreach ($items as $item) {

            $payload = $payloadFactory($item);

            $results[] = $this->pipeline->process(
                $payload
            );

        }

        return $results;
    }
}