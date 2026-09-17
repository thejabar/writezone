<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation\Evaluators;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Analysis\ReadabilityReport;
use App\Intelligence\Analysis\VocabularyReport;
use App\Intelligence\Contracts\Evaluator;

final class StructureEvaluator implements Evaluator
{
    public function name(): string
    {
        return 'structure';
    }

    public function evaluate(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): float {

        $score = 0.0;

        if ($metrics->paragraphs >= 2) {
            $score += 35;
        } elseif ($metrics->paragraphs === 1 && $metrics->words >= 80) {
            $score += 20;
        }

        if ($metrics->sentences >= 3) {
            $score += 30;
        } elseif ($metrics->sentences >= 2) {
            $score += 20;
        }

        if ($metrics->words >= 80) {
            $score += 35;
        } elseif ($metrics->words >= 40) {
            $score += 20;
        } elseif ($metrics->words >= 20) {
            $score += 10;
        }

        return min(100.0, $score);
    }

    public function strengths(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): array {

        $strengths = [];

        if ($metrics->paragraphs >= 2) {
            $strengths[] =
                'Content is organised into multiple paragraphs.';
        }

        if ($metrics->sentences >= 3) {
            $strengths[] =
                'Content contains a developed sentence structure.';
        }

        if ($metrics->words >= 80) {
            $strengths[] =
                'Content contains enough material for meaningful structural analysis.';
        }

        return $strengths;
    }

    public function suggestions(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): array {

        $suggestions = [];

        if ($metrics->paragraphs < 2 && $metrics->words >= 80) {
            $suggestions[] =
                'Consider separating major ideas into multiple paragraphs.';
        }

        if ($metrics->sentences < 3) {
            $suggestions[] =
                'Develop the content with more complete sentences.';
        }

        if ($metrics->words < 40) {
            $suggestions[] =
                'Add more supporting detail so the structure can be evaluated more meaningfully.';
        }

        return $suggestions;
    }
}
