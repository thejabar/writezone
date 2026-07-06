<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Contracts\Evaluator;
use App\Intelligence\Evaluation\Evaluators\ReadabilityEvaluator;
use App\Intelligence\Evaluation\Evaluators\StructureEvaluator;
use App\Intelligence\Evaluation\Evaluators\VocabularyEvaluator;

final class QualityEngine
{
    /**
     * @var Evaluator[]
     */
    private array $evaluators;

    public function __construct()
    {
        $this->evaluators = [
            new StructureEvaluator(),
            new ReadabilityEvaluator(),
            new VocabularyEvaluator(),
        ];
    }

    public function evaluate(
        ContentMetrics $metrics
    ): QualityResult {

        $breakdown = new QualityBreakdown();

        $strengths = [];

        $suggestions = [];

        foreach ($this->evaluators as $evaluator) {

            $score = $evaluator->evaluate($metrics);

            $breakdown->add(
                $evaluator->name(),
                $score
            );

            $strengths = array_merge(
                $strengths,
                $evaluator->strengths($metrics)
            );

            $suggestions = array_merge(
                $suggestions,
                $evaluator->suggestions($metrics)
            );
        }

        return new QualityResult(
            score: min(
                100,
                $breakdown->total()
            ),
            breakdown: $breakdown->toArray(),
            strengths: $strengths,
            suggestions: $suggestions
        );
    }
}