<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation\Evaluators;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Contracts\Evaluator;

final class ReadabilityEvaluator implements Evaluator
{
    public function name(): string
    {
        return 'readability';
    }

    public function evaluate(
        ContentMetrics $metrics
    ): float {

        $score = 0;

        if ($metrics->sentences >= 5) {
            $score += 30;
        }

        if (
            $metrics->paragraphs >= 2 &&
            $metrics->paragraphs <= 8
        ) {
            $score += 30;
        }

        if (
            $metrics->words >= 100 &&
            $metrics->words <= 800
        ) {
            $score += 40;
        }

        return min(
            $score,
            100
        );
    }

    public function strengths(
        ContentMetrics $metrics
    ): array {

        $strengths = [];

        if ($metrics->paragraphs >= 2) {
            $strengths[] =
                'Paragraphs improve readability.';
        }

        if ($metrics->sentences >= 5) {
            $strengths[] =
                'Content contains sufficient sentence structure.';
        }

        return $strengths;
    }

    public function suggestions(
        ContentMetrics $metrics
    ): array {

        $suggestions = [];

        if ($metrics->paragraphs < 2) {
            $suggestions[] =
                'Split the content into additional paragraphs.';
        }

        if ($metrics->sentences < 5) {
            $suggestions[] =
                'Expand the content using more complete sentences.';
        }

        if ($metrics->words < 100) {
            $suggestions[] =
                'Add more detail to improve readability.';
        }

        return $suggestions;
    }
}