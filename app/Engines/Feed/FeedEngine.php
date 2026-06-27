<?php

declare(strict_types=1);

namespace App\Engines\Feed;
use App\Engines\Engine;
use App\Models\Writ;

class FeedEngine extends Engine
{
    public function execute(
        array $payload = []
    ): array {

        $feed = Writ::feed();

        /*
         * Future pipeline:
         *
         * $feed = (new RelationshipEngine())
         *     ->process($feed);
         *
         * $feed = (new FreshnessEngine())
         *     ->process($feed);
         *
         * $feed = (new QualityEngine())
         *     ->process($feed);
         *
         * $feed = (new DiscoveryEngine())
         *     ->process($feed);
         *
         * $feed = (new DiversityEngine())
         *     ->process($feed);
         *
         * $feed = (new RankingEngine())
         *     ->process($feed);
         */

        return $feed;
    }
}