<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation\Evaluators;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Contracts\Evaluator;

final class StructureEvaluator implements Evaluator
{
    public function name(): string
    {
        return 'structure';
    }

    public function evaluate(
        ContentMetrics $metrics
    ): float {

        $score = 0;

        if ($metrics->paragraphs >= 2) {
            $score += 30;
        }

        if ($metrics->sentences >= 3) {
            $score += 30;
        }

        if ($metrics->words >= 80) {
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
                'Well organized into multiple paragraphs.';
        }

        if ($metrics->sentences >= 3) {
            $strengths[] =
                'Contains a healthy number of complete sentences.';
        }

        return $strengths;
    }

    public function suggestions(
        ContentMetrics $metrics
    ): array {

        $suggestions = [];

        if ($metrics->paragraphs < 2) {
            $suggestions[] =
                'Split the content into multiple paragraphs.';
        }

        if ($metrics->sentences < 3) {
            $suggestions[] =
                'Expand your ideas into more complete sentences.';
        }

        if ($metrics->words < 80) {
            $suggestions[] =
                'Consider adding more supporting detail.';
        }

        return $suggestions;
    }
}