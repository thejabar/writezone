<?php

declare(strict_types=1);

namespace App\Ranking\Contracts;

use App\Feed\FeedCandidate;

interface Scorer
{
    /**
     * Returns the unique scorer name.
     */
    public function name(): string;

    /**
     * Calculates the score contribution.
     */
    public function score(
        FeedCandidate $candidate
    ): float;

    /**
     * Returns a human-readable explanation.
     */
    public function reason(
        FeedCandidate $candidate
    ): string;
}
