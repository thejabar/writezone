<?php

declare(strict_types=1);

namespace App\Ranking\Support;

final class ScoreBreakdown
{
    /**
     * @var array<string,float>
     */
    private array $scores = [];

    /**
     * Store one scorer contribution.
     */
    public function add(
        string $name,
        float $score
    ): void {
        $this->scores[$name] = $score;
    }

    /**
     * Retrieve one scorer value.
     */
    public function get(
        string $name
    ): float {
        return $this->scores[$name] ?? 0.0;
    }

    /**
     * Return all scorer values.
     *
     * @return array<string,float>
     */
    public function all(): array
    {
        return $this->scores;
    }

    /**
     * Total score.
     */
    public function total(): float
    {
        return array_sum(
            $this->scores
        );
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
