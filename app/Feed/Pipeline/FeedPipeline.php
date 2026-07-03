<?php

declare(strict_types=1);

namespace App\Feed\Pipeline;

use App\Intelligence\Processors\FreshnessProcessor;
use App\Intelligence\Processors\RelationshipProcessor;
use App\Pipeline\Pipeline;

final class FeedPipeline
{
    /**
     * Process feed candidates through the intelligence pipeline.
     *
     * @param array $candidates
     * @return array
     */
    public function process(
        array $candidates
    ): array {

        return (new Pipeline())
            ->through(
                new RelationshipProcessor()
            )
            ->through(
                new FreshnessProcessor()
            )
            ->process(
                $candidates
            );

    }
}
