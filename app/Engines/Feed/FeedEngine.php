<?php

declare(strict_types=1);

namespace App\Engines\Feed;

use App\Engines\Engine;
use App\Feed\FeedCandidateFactory;
use App\Feed\Pipeline\FeedPipeline;
use App\Feed\Results\FeedResult;
use App\Models\Writ;
use App\Ranking\Engines\RankingEngine;
use App\Support\Collections\ArrayCollection;

final class FeedEngine extends Engine
{
    public function execute(
        array $payload = []
    ): FeedResult {

        $rows = Writ::feed();

        $candidates = FeedCandidateFactory::fromFeed(
            $rows,
            $payload
        );

        $pipeline = new FeedPipeline();

        $ranking = new RankingEngine();

        $processed = $pipeline->process(
            $candidates
        );

        $ranked = $ranking->rankAll(
            $processed
        );

        return new FeedResult(
            candidates: new ArrayCollection(
                $ranked
            ),
            metadata: $payload
        );
    }
}