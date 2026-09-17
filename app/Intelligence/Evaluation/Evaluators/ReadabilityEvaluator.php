<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation\Evaluators;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Analysis\ReadabilityReport;
use App\Intelligence\Analysis\VocabularyReport;
use App\Intelligence\Contracts\Evaluator;

final class ReadabilityEvaluator implements Evaluator
{
    public function name(): string
    {
        return 'readability';
    }

    public function evaluate(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): float {

        if ($readability !== null) {
            return max(
                0.0,
                min(
                    100.0,
                    $readability->score
                )
            );
        }

        return 0.0;
    }

    public function strengths(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): array {

        if ($readability === null) {
            return [];
        }

        $strengths = [];

        if ($readability->readingFlow === 'Smooth') {
            $strengths[] =
                'The writing has a smooth reading flow.';
        }

        if ($readability->difficulty === 'Easy') {
            $strengths[] =
                'The writing is easy to read.';
        }

        if ($readability->paragraphBalance === 'Excellent') {
            $strengths[] =
                'Paragraph balance is strong.';
        }

        return $strengths;
    }

    public function suggestions(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): array {

        if ($readability === null) {
            return [];
        }

        $suggestions = [];

        if ($readability->longSentences > 0) {
            $suggestions[] =
                'Consider shortening some long sentences to improve readability.';
        }

        if ($readability->readingFlow !== 'Smooth') {
            $suggestions[] =
                'Review sentence and paragraph flow for smoother reading.';
        }

        if ($readability->paragraphBalance === 'Fair') {
            $suggestions[] =
                'Consider balancing paragraph lengths.';
        }

        return $suggestions;
    }
}
