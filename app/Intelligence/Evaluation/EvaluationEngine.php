<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Contracts\Evaluator;

final class EvaluationEngine
{
    /**
     * @param Evaluator[] $evaluators
     */
    public function __construct(
        private readonly array $evaluators
    ) {
    }

    /**
     * Run every evaluator against the supplied metrics.
     */
    public function evaluate(
        ContentMetrics $metrics
    ): EvaluationCollection {

        $results = new EvaluationCollection();

        foreach ($this->evaluators as $evaluator) {

            $results->add(

                new EvaluationResult(

                    name: $evaluator->name(),

                    score: $evaluator->evaluate(
                        $metrics
                    ),

                    breakdown: [],

                    suggestions: $evaluator->suggestions(
                        $metrics
                    ),

                    metadata: [

                        'strengths' => $evaluator->strengths(
                            $metrics
                        ),

                    ]

                )

            );

        }

        return $results;
    }

    /**
     * Number of registered evaluators.
     */
    public function count(): int
    {
        return count(
            $this->evaluators
        );
    }

    /**
     * Determine whether the engine has evaluators.
     */
    public function isEmpty(): bool
    {
        return empty(
            $this->evaluators
        );
    }
}