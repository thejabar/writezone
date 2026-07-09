<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

final class AnalysisMetadata
{
    public function __construct(
        private readonly string $engineVersion,
        private readonly int $evaluatorCount,
        private readonly int $metricCount,
        private readonly float $executionTime,
        private readonly \DateTimeImmutable $generatedAt,
    ) {
    }

    /**
     * Intelligence Engine version.
     */
    public function engineVersion(): string
    {
        return $this->engineVersion;
    }

    /**
     * Number of evaluators executed.
     */
    public function evaluatorCount(): int
    {
        return $this->evaluatorCount;
    }

    /**
     * Number of collected metrics.
     */
    public function metricCount(): int
    {
        return $this->metricCount;
    }

    /**
     * Execution time in milliseconds.
     */
    public function executionTime(): float
    {
        return $this->executionTime;
    }

    /**
     * Analysis generation timestamp.
     */
    public function generatedAt(): \DateTimeImmutable
    {
        return $this->generatedAt;
    }
}
