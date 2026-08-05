<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentAnalyzer;
use App\Intelligence\Analysis\PunctuationAnalyzer;
use App\Intelligence\Analysis\SentenceAnalyzer;
use App\Intelligence\Analysis\WordAnalyzer;

final class AnalysisContextBuilder
{
    public function build(
        string $content
    ): AnalysisContext {

        /*
        |--------------------------------------------------------------------------
        | Core Intelligence
        |--------------------------------------------------------------------------
        */

        $metrics = (new ContentAnalyzer())
            ->analyze(
                $content
            );

        $sentences = (new SentenceAnalyzer())
            ->analyze(
                $content
            );

        $words = (new WordAnalyzer())
            ->analyze(
                $content
            );

        $punctuation = (new PunctuationAnalyzer())
            ->analyze(
                $content
            );

        /*
        |--------------------------------------------------------------------------
        | Shared Context
        |--------------------------------------------------------------------------
        */

        return new AnalysisContext(

            content: $content,

            metrics: $metrics,

            sentences: $sentences,

            words: $words,

            punctuation: $punctuation,

        );

    }
}