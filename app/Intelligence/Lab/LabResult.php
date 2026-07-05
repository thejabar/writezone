<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Evaluation\QualityResult;

final class LabResult
{
    public function __construct(
        private readonly ContentMetrics $metrics,
        private readonly array $signals = [],
        private readonly float $score = 0.0
    ) {
    }

    public function metrics(): ContentMetrics
    {
        return $this->metrics;
    }

    public function signals(): array
    {
        return $this->signals;
    }

    public function quality(): ?QualityResult
    {
        $quality = $this->signals['quality'] ?? null;

        return $quality instanceof QualityResult
            ? $quality
            : null;
    }

    public function score(): float
    {
        return $this->score;
    }
}