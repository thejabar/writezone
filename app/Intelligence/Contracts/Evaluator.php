<?php

declare(strict_types=1);

namespace App\Intelligence\Contracts;

use App\Intelligence\Analysis\ContentMetrics;

interface Evaluator
{
    /**
     * Unique evaluator name.
     */
    public function name(): string;

    /**
     * Evaluate one aspect of the content.
     */
    public function evaluate(
        ContentMetrics $metrics
    ): float;

    /**
     * Positive observations.
     *
     * @return string[]
     */
    public function strengths(
        ContentMetrics $metrics
    ): array;

    /**
     * Coaching suggestions.
     *
     * @return string[]
     */
    public function suggestions(
        ContentMetrics $metrics
    ): array;
}