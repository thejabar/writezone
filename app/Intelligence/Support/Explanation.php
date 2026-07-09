<?php

declare(strict_types=1);

namespace App\Intelligence\Support;

final class Explanation
{
    /**
     * @param string[] $strengths
     * @param string[] $suggestions
     */
    public function __construct(
        private readonly string $reason,
        private readonly string $confidence,
        private readonly array $strengths = [],
        private readonly array $suggestions = [],
    ) {
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function confidence(): string
    {
        return $this->confidence;
    }

    /**
     * @return string[]
     */
    public function strengths(): array
    {
        return $this->strengths;
    }

    /**
     * @return string[]
     */
    public function suggestions(): array
    {
        return $this->suggestions;
    }
}
