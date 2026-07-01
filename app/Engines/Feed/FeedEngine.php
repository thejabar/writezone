<?php

declare(strict_types=1);

namespace App\Engines\Feed;

use App\Engines\Engine;
use App\Feed\FeedCandidateFactory;
use App\Intelligence\Processors\RelationshipProcessor;
use App\Models\Writ;
use App\Pipeline\Pipeline;

final class FeedEngine extends Engine
{
    public function execute(
        array $payload = []
    ): array {

        $rows = Writ::feed();

        $candidates = FeedCandidateFactory::fromFeed(
            $rows,
            $payload
        );

        $pipeline = (new Pipeline())
            ->through(
                new RelationshipProcessor()
            );

        return $pipeline->process(
            $candidates
        );

    }
}