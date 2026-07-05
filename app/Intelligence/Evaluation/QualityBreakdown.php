<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation;

final class QualityBreakdown
{
    /**
     * @var array<string,float>
     */
    private array $scores = [];

    /**
     * Add one evaluator score.
     */
    public function add(
        string $name,
        float $score
    ): void {

        $this->scores[$name] = $score;

    }

    /**
     * Get one evaluator score.
     */
    public function get(
        string $name
    ): float {

        return $this->scores[$name] ?? 0.0;

    }

    /**
     * Check whether an evaluator exists.
     */
    public function has(
        string $name
    ): bool {

        return array_key_exists(
            $name,
            $this->scores
        );

    }

    /**
     * Total quality score.
     */
    public function total(): float
    {

        return array_sum(
            $this->scores
        );

    }

    /**
     * Number of evaluators.
     */
    public function count(): int
    {

        return count(
            $this->scores
        );

    }

    /**
     * Export all evaluator scores.
     *
     * @return array<string,float>
     */
    public function all(): array
    {

        return $this->scores;

    }

    /**
     * Export as array.
     *
     * @return array<string,float>
     */
    public function toArray(): array
    {

        return $this->scores;

    }
}