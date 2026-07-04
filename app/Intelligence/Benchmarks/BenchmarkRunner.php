<?php

declare(strict_types=1);

namespace App\Intelligence\Benchmarks;

use App\Intelligence\Analysis\ContentAnalyzer;
use App\Intelligence\Evaluators\QualityEvaluator;

final class BenchmarkRunner
{
    public function run(
        BenchmarkSuite $suite
    ): array {

        $analyzer = new ContentAnalyzer();

        $evaluator = new QualityEvaluator();

        $results = [];

        foreach ($suite->cases() as $case) {

            $metrics = $analyzer->analyze(
                $case->content()
            );

            $quality = $evaluator->evaluate(
                $metrics
            );

            $results[] = [

                'name' => $case->name(),

                'quality_score' => $quality,

                'expectations' => $case->expectations(),

                'metrics' => $metrics,

            ];

        }

        return $results;
    }
}
