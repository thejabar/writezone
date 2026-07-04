<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentAnalyzer;
use App\Intelligence\Evaluators\QualityEvaluator;

final class IntelligenceLab
{
    public function analyze(
        LabRequest $request
    ): LabResult {

        $analyzer = new ContentAnalyzer();

        $metrics = $analyzer->analyze(
            $request->content()
        );

        $quality = (new QualityEvaluator())
            ->evaluate($metrics);

        return new LabResult(
            metrics: $metrics,
            signals: [
                'quality' => $quality,
            ],
            score: $quality
        );
    }
}
