<?php

declare(strict_types=1);

namespace App\Ranking\Scorers;

use App\Feed\FeedCandidate;
use App\Ranking\Contracts\Scorer;
use App\Ranking\Policies\RankingPolicy;

final class FreshnessScorer implements Scorer
{
    public function __construct(
        private readonly RankingPolicy $policy = new RankingPolicy()
    ) {
    }

    public function name(): string
    {
        return 'freshness';
    }

    public function score(
        FeedCandidate $candidate
    ): float {

        $signal = $candidate
            ->signals()
            ->get('freshness');

        if ($signal === null) {
            return 0.0;
        }

        return $signal->value()
            * $this->policy->weight(
                $this->name()
            );
    }

    public function reason(
        FeedCandidate $candidate
    ): string {

        $signal = $candidate
            ->signals()
            ->get('freshness');

        return $signal?->reason()
            ?? 'Freshness signal unavailable';
    }
}