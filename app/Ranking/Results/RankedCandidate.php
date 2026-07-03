<?php

declare(strict_types=1);

namespace App\Ranking\Results;

use App\Feed\FeedCandidate;
use App\Feed\FeedItem;
use App\Intelligence\Collections\SignalCollection;

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
     * Feed item.
     */
    public function item(): FeedItem
    {
        return $this->candidate->item;
    }

    /**
     * Intelligence signals.
     */
    public function signals(): SignalCollection
    {
        return $this->candidate->signals;
    }

    /**
     * Ranking result.
     */
    public function ranking(): RankingResult
    {
        return $this->ranking;
    }

    /**
     * Final score.
     */
    public function score(): float
    {
        return $this->ranking->score();
    }

    /**
     * Score breakdown.
     */
    public function breakdown(): array
    {
        return $this->ranking
            ->breakdown()
            ->toArray();
    }

    /**
     * Confidence score.
     */
    public function confidence(): float
    {
        return $this->ranking
            ->confidence();
    }
}