<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentAnalyzer;
use App\Intelligence\Analysis\VocabularyAnalyzer;
use App\Intelligence\Analysis\SentenceAnalyzer;
use App\Intelligence\Evaluation\QualityEngine;

final class IntelligenceLab
{
    public function analyze(
        LabRequest $request
    ): LabResult {

        $started = microtime(true);

        $metrics = (new ContentAnalyzer())
            ->analyze(
                $request->content()
            );
        $vocabulary = (new VocabularyAnalyzer())
    ->analyze(
        $request->content()
    );
        $sentences = (new SentenceAnalyzer())
    ->analyze(
        $request->content()
    );

        $quality = (new QualityEngine())
            ->evaluate($metrics);

        $metadata = new AnalysisMetadata(
            engineVersion: '0.7.0',
            evaluatorCount: count(
                $quality->breakdown()
            ),
            metricCount: 8,
            executionTime: round(
                (microtime(true) - $started) * 1000,
                2
            ),
            generatedAt: new \DateTimeImmutable()
        );

        $report = new AnalysisReport(
            metrics: $metrics,
            quality: $quality,
            sentences: $sentences,
            metadata: $metadata
        );

        return new LabResult(
            report: $report
        );
    }
}