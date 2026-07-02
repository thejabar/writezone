<?php

declare(strict_types=1);

namespace App\Ranking\Results;

use App\Feed\FeedCandidate;

final class RankedCandidate
{
    public function __construct(
        private readonly FeedCandidate $candidate,
        private readonly RankingResult $ranking
    ) {
    }

    /**
     * Original candidate.
     */
    public function candidate(): FeedCandidate
    {
        return $this->candidate;
    }

    /**
     * Ranking result.
     */
    public function ranking(): RankingResult
    {
        return $this->ranking;
    }

    /**
     * Convenience helper.
     */
    public function score(): float
    {
        return $this->ranking->score();
    }

    /**
     * Convenience helper.
     */
    public function breakdown(): array
    {
        return $this->ranking
            ->breakdown()
            ->toArray();
    }

    /**
     * Convenience helper.
     */
    public function confidence(): float
    {
        return $this->ranking
            ->confidence();
    }
}