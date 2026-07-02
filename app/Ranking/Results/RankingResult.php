<?php

declare(strict_types=1);

namespace App\Ranking\Results;

use App\Ranking\Support\ScoreBreakdown;

final class RankingResult
{
    public function __construct(
        private readonly float $score,
        private readonly ScoreBreakdown $breakdown,
        private readonly float $confidence = 1.0
    ) {
    }

    /**
     * Final ranking score.
     */
    public function score(): float
    {
        return $this->score;
    }

    /**
     * Individual scorer contributions.
     */
    public function breakdown(): ScoreBreakdown
    {
        return $this->breakdown;
    }

    /**
     * Confidence in the calculated score.
     */
    public function confidence(): float
    {
        return $this->confidence;
    }

    /**
     * Convert the result into an array.
     */
    public function toArray(): array
    {
        return [
            'score' => $this->score,
            'breakdown' => $this->breakdown->toArray(),
            'confidence' => $this->confidence,
        ];
    }
}