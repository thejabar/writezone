<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation;

final class EvaluationResult
{
    /**
     * @param array<string,float> $breakdown
     * @param string[] $suggestions
     * @param array<string,mixed> $metadata
     */
    public function __construct(
        private readonly string $name,
        private readonly float $score,
        private readonly array $breakdown = [],
        private readonly array $suggestions = [],
        private readonly array $metadata = []
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function score(): float
    {
        return $this->score;
    }

    /**
     * @return array<string,float>
     */
    public function breakdown(): array
    {
        return $this->breakdown;
    }

    /**
     * @return string[]
     */
    public function suggestions(): array
    {
        return $this->suggestions;
    }

    /**
     * @return array<string,mixed>
     */
    public function metadata(): array
    {
        return $this->metadata;
    }
}