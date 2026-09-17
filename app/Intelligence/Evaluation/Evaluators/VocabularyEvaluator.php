<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation\Evaluators;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Analysis\ReadabilityReport;
use App\Intelligence\Analysis\VocabularyReport;
use App\Intelligence\Contracts\Evaluator;

final class VocabularyEvaluator implements Evaluator
{
    public function name(): string
    {
        return 'vocabulary';
    }

    public function evaluate(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): float {

        if ($vocabulary === null) {
            return 0.0;
        }

        $score = $vocabulary->lexicalDiversity;

        if ($vocabulary->fillerWords > 0) {
            $score -= min(
                15,
                $vocabulary->fillerWords * 2
            );
        }

        if ($vocabulary->totalWords > 0) {
            $repetitionRate =
                (
                    $vocabulary->repeatedWords
                    / $vocabulary->totalWords
                ) * 100;

            if ($repetitionRate > 25) {
                $score -= min(
                    15,
                    $repetitionRate - 25
                );
            }
        }

        return max(
            0.0,
            min(
                100.0,
                $score
            )
        );
    }

    public function strengths(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): array {

        if ($vocabulary === null) {
            return [];
        }

        $strengths = [];

        if ($vocabulary->lexicalDiversity >= 70) {
            $strengths[] =
                'Vocabulary shows good lexical diversity.';
        }

        if ($vocabulary->fillerWords === 0) {
            $strengths[] =
                'No common filler words were detected.';
        }

        if ($vocabulary->transitionWords > 0) {
            $strengths[] =
                'Transition words are being used to connect ideas.';
        }

        return $strengths;
    }

    public function suggestions(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): array {

        if ($vocabulary === null) {
            return [];
        }

        $suggestions = [];

        if ($vocabulary->lexicalDiversity < 50) {
            $suggestions[] =
                'Consider using a wider range of vocabulary.';
        }

        if ($vocabulary->fillerWords > 0) {
            $suggestions[] =
                'Reduce unnecessary filler words where possible.';
        }

        if (
            $vocabulary->totalWords >= 50 &&
            $vocabulary->transitionWords === 0
        ) {
            $suggestions[] =
                'Consider using transition words where they improve the connection between ideas.';
        }

        return $suggestions;
    }
}
