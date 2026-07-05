<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentAnalyzer;
use App\Intelligence\Evaluation\QualityEngine;

final class IntelligenceLab
{
    public function analyze(
        LabRequest $request
    ): LabResult {

        $analyzer = new ContentAnalyzer();

        $metrics = $analyzer->analyze(
            $request->content()
        );

        $quality = (new QualityEngine())
    ->evaluate($metrics);

        return new LabResult(
            metrics: $metrics,
            signals: [
    'quality' => $quality,
],
score: $quality->score()
        );
    }
}
