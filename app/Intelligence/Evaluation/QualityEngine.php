<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Analysis\ReadabilityReport;
use App\Intelligence\Analysis\VocabularyReport;
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
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): QualityResult {

        $breakdown = new QualityBreakdown();

        $strengths = [];

        $suggestions = [];

        foreach ($this->evaluators as $evaluator) {

            $score = $evaluator->evaluate(
                $metrics,
                $vocabulary,
                $readability
            );

            $breakdown->add(
                $evaluator->name(),
                $score
            );

            $strengths = array_merge(
                $strengths,
                $evaluator->strengths(
                    $metrics,
                    $vocabulary,
                    $readability
                )
            );

            $suggestions = array_merge(
                $suggestions,
                $evaluator->suggestions(
                    $metrics,
                    $vocabulary,
                    $readability
                )
            );
        }

        $scores = $breakdown->all();

        $score =
            $scores === []
                ? 0.0
                : array_sum($scores) / count($scores);

        return new QualityResult(
            score: round(
                max(0.0, min(100.0, $score)),
                2
            ),
            breakdown: $breakdown->toArray(),
            strengths: array_values(
                array_unique($strengths)
            ),
            suggestions: array_values(
                array_unique($suggestions)
            )
        );
    }
}
