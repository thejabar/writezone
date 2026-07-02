<?php

declare(strict_types=1);

namespace App\Ranking\Policies;

final class RankingPolicy
{
    /**
     * Default scorer weights.
     *
     * Total should equal 1.0
     */
    private array $weights = [
        'relationship' => 0.40,
        'freshness'    => 0.60,
    ];

    /**
     * Get one scorer weight.
     */
    public function weight(
        string $name
    ): float {
        return $this->weights[$name] ?? 0.0;
    }

    /**
     * Return all configured weights.
     *
     * @return array<string,float>
     */
    public function all(): array
    {
        return $this->weights;
    }

    /**
     * Determine whether a scorer exists.
     */
    public function has(
        string $name
    ): bool {
        return array_key_exists(
            $name,
            $this->weights
        );
    }

    /**
     * Sum of all weights.
     */
    public function totalWeight(): float
    {
        return array_sum(
            $this->weights
        );
    }
}
