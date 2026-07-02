<?php

declare(strict_types=1);

namespace App\Ranking\Engines;

use App\Feed\FeedCandidate;
use App\Ranking\Results\RankedCandidate;
use App\Ranking\Contracts\Scorer;
use App\Ranking\Policies\RankingPolicy;
use App\Ranking\Results\RankingResult;
use App\Ranking\Support\ScoreBreakdown;
use App\Ranking\Scorers\RelationshipScorer;
use App\Ranking\Scorers\FreshnessScorer;

final class RankingEngine
{
    /**
     * @var Scorer[]
     */
    private array $scorers;

    public function __construct(
    ?array $scorers = null
) {

    $policy = new RankingPolicy();

    $this->scorers = $scorers ?? [
        new RelationshipScorer(
            $policy
        ),
        new FreshnessScorer(
            $policy
        ),
    ];

}

    public function rank(
    FeedCandidate $candidate
): RankedCandidate {

    $breakdown = new ScoreBreakdown();

    foreach (
        $this->scorers
        as $scorer
    ) {

        $breakdown->add(
            $scorer->name(),
            $scorer->score(
                $candidate
            )
        );

    }

    $result = new RankingResult(
        score: $breakdown->total(),
        breakdown: $breakdown,
        confidence: 1.0
    );

    return new RankedCandidate(
        candidate: $candidate,
        ranking: $result
    );

}

    /**
     * Rank multiple candidates.
     *
     * @param FeedCandidate[] $candidates
     * @return RankedCandidate[]
     */
    public function rankAll(
        array $candidates
    ): array {

        return array_map(
            fn (
                FeedCandidate $candidate
            ) => $this->rank(
                $candidate
            ),
            $candidates
        );

    }
}