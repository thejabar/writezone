<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation;

final class QualityResult
{
    /**
     * @param array<string,float> $breakdown
     * @param string[] $strengths
     * @param string[] $suggestions
     */
    public function __construct(
        private readonly float $score,
        private readonly array $breakdown,
        private readonly array $strengths,
        private readonly array $suggestions,
    ) {
    }

    /**
     * Overall quality score.
     */
    public function score(): float
    {
        return $this->score;
    }

    /**
     * Individual evaluator scores.
     *
     * @return array<string,float>
     */
    public function breakdown(): array
    {
        return $this->breakdown;
    }

    /**
     * Positive observations.
     *
     * @return string[]
     */
    public function strengths(): array
    {
        return $this->strengths;
    }

    /**
     * Coaching suggestions.
     *
     * @return string[]
     */
    public function suggestions(): array
    {
        return $this->suggestions;
    }

    /**
     * Determine whether any strengths exist.
     */
    public function hasStrengths(): bool
    {
        return $this->strengths !== [];
    }

    /**
     * Determine whether any suggestions exist.
     */
    public function hasSuggestions(): bool
    {
        return $this->suggestions !== [];
    }

    /**
     * Determine whether any evaluator breakdown exists.
     */
    public function hasBreakdown(): bool
    {
        return $this->breakdown !== [];
    }

    /**
     * Retrieve the score for a single evaluation dimension.
     */
    public function scoreFor(
        string $dimension
    ): ?float {

        return $this->breakdown[$dimension] ?? null;
    }
}