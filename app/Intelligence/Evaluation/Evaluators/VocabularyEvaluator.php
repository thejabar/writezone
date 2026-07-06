<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation\Evaluators;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Contracts\Evaluator;

final class VocabularyEvaluator implements Evaluator
{
    public function name(): string
    {
        return 'vocabulary';
    }

    public function evaluate(
        ContentMetrics $metrics
    ): float {

        $score = 0;

        if ($metrics->words >= 50) {
            $score += 25;
        }

        if ($metrics->words >= 100) {
            $score += 35;
        }

        if ($metrics->words >= 250) {
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

        if ($metrics->words >= 100) {
            $strengths[] =
                'Good amount of written content.';
        }

        if ($metrics->words >= 250) {
            $strengths[] =
                'Content provides room for vocabulary diversity.';
        }

        return $strengths;
    }

    public function suggestions(
        ContentMetrics $metrics
    ): array {

        $suggestions = [];

        if ($metrics->words < 50) {
            $suggestions[] =
                'Expand the content using more descriptive language.';
        }

        if ($metrics->words < 100) {
            $suggestions[] =
                'Introduce more varied vocabulary.';
        }

        return $suggestions;
    }
}