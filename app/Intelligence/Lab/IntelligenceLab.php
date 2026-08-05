<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\GrammarAnalyzer;
use App\Intelligence\Analysis\ReadabilityAnalyzer;
use App\Intelligence\Analysis\VocabularyAnalyzer;
use App\Intelligence\Evaluation\QualityEngine;
use App\Intelligence\Grammar\Diagnostics\GrammarDiagnosticsGenerator;
use App\Intelligence\Grammar\GrammarFeedbackGenerator;
use App\Intelligence\Summary\ExecutiveSummaryGenerator;

final class IntelligenceLab
{
    public function analyze(
        LabRequest $request
    ): LabResult {

        $started = microtime(true);

        $content = $request->content();

        /*
        |--------------------------------------------------------------------------
        | Build Shared Intelligence
        |--------------------------------------------------------------------------
        */

        $context = (new AnalysisContextBuilder())
            ->build(
                $content
            );

        /*
        |--------------------------------------------------------------------------
        | Shared Reports
        |--------------------------------------------------------------------------
        */

        $metrics = $context->metrics();

        $sentences = $context->sentences();

        /*
        |--------------------------------------------------------------------------
        | Specialized Intelligence
        |--------------------------------------------------------------------------
        */

        $vocabulary = (new VocabularyAnalyzer())
            ->analyze(
                $context
            );

        $readability = (new ReadabilityAnalyzer())
            ->analyze(
                $context
            );

        $grammar = (new GrammarAnalyzer())
            ->analyze(
                $context
            );

        /*
        |--------------------------------------------------------------------------
        | Grammar Intelligence
        |--------------------------------------------------------------------------
        */

        $grammarFeedback =
            (new GrammarFeedbackGenerator())
                ->generate(
                    $grammar
                );

        $grammarDiagnostics =
            (new GrammarDiagnosticsGenerator())
                ->generate(
                    $grammar
                );

        /*
        |--------------------------------------------------------------------------
        | Quality Evaluation
        |--------------------------------------------------------------------------
        */

        $quality = (new QualityEngine())
            ->evaluate(
                $metrics
            );

        /*
        |--------------------------------------------------------------------------
        | Analysis Metadata
        |--------------------------------------------------------------------------
        */

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

            generatedAt: new \DateTimeImmutable(),

        );

        /*
        |--------------------------------------------------------------------------
        | Initial Analysis Report
        |--------------------------------------------------------------------------
        */

        $report = new AnalysisReport(

            metrics: $metrics,

            quality: $quality,

            sentences: $sentences,

            vocabulary: $vocabulary,

            readability: $readability,

            grammar: $grammar,

            grammarFeedback: $grammarFeedback,

            grammarDiagnostics: $grammarDiagnostics,

            executiveSummary: null,

            metadata: $metadata,

        );

        /*
        |--------------------------------------------------------------------------
        | Executive Summary
        |--------------------------------------------------------------------------
        */

        $executiveSummary =
            (new ExecutiveSummaryGenerator())
                ->generate(
                    $report
                );

        /*
        |--------------------------------------------------------------------------
        | Final Analysis Report
        |--------------------------------------------------------------------------
        */

        $report = new AnalysisReport(

            metrics: $metrics,

            quality: $quality,

            sentences: $sentences,

            vocabulary: $vocabulary,

            readability: $readability,

            grammar: $grammar,

            grammarFeedback: $grammarFeedback,

            grammarDiagnostics: $grammarDiagnostics,

            executiveSummary: $executiveSummary,

            metadata: $metadata,

        );

        return new LabResult(

            report: $report,

        );

    }
}