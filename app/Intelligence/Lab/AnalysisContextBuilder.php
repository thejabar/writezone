<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentAnalyzer;
use App\Intelligence\Analysis\PunctuationAnalyzer;
use App\Intelligence\Analysis\SentenceAnalyzer;
use App\Intelligence\Analysis\WordAnalyzer;
use App\Intelligence\Language\LanguageDefinition;
use App\Intelligence\Language\LanguageRegistry;

final class AnalysisContextBuilder
{
    public function build(
        string $content,
        ?LanguageDefinition $language = null
    ): AnalysisContext {

        $language = $language
            ?? LanguageRegistry::default();

        /*
        |--------------------------------------------------------------------------
        | Core Intelligence
        |--------------------------------------------------------------------------
        */

        $metrics = (new ContentAnalyzer())
            ->analyze(
                $content,
                $language
            );

        $sentences = (new SentenceAnalyzer())
            ->analyze(
                $content,
                $language
            );

        $words = (new WordAnalyzer())
            ->analyze(
                $content,
                $language
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

            language: $language,

            metrics: $metrics,

            sentences: $sentences,

            words: $words,

            punctuation: $punctuation,

        );

    }
}
