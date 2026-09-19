<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentAnalyzer;
use App\Intelligence\Analysis\PunctuationAnalyzer;
use App\Intelligence\Analysis\SentenceAnalyzer;
use App\Intelligence\Analysis\WordAnalyzer;
use App\Intelligence\Language\LanguageDefinition;

final class AnalysisContextBuilder
{
    public function build(
        string $content,
        ?LanguageDefinition $language = null
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

            language:
                $language
                ?? \App\Intelligence\Language\LanguageRegistry::default(),

            metrics: $metrics,

            sentences: $sentences,

            words: $words,

            punctuation: $punctuation,

        );

    }
}