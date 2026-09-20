<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

use App\Intelligence\Language\LanguageDefinition;
use App\Intelligence\Language\LanguageRegistry;
use App\Intelligence\Language\Text\LanguageTextProcessor;
use App\Intelligence\Support\SentenceParser;

final class SentenceAnalyzer
{
    public function analyze(
        string $content,
        ?LanguageDefinition $language = null
    ): SentenceReport {

        $content = trim($content);

        if ($content === '') {
            return new SentenceReport(
                total: 0,
                shortest: 0,
                longest: 0,
                average: 0,
                variety: 0,
            );
        }

        $language = $language ?? LanguageRegistry::default();

        $sentences = SentenceParser::parse(
            $content,
            $language
        );

        $total = count($sentences);

        if ($total === 0) {
            return new SentenceReport(
                total: 0,
                shortest: 0,
                longest: 0,
                average: 0,
                variety: 0,
            );
        }

        $processor = new LanguageTextProcessor();

        $lengths = [];

        foreach ($sentences as $sentence) {
            $sentence = preg_replace(
                '/[.!?؟。！？]+$/u',
                '',
                trim($sentence)
            );

            $lengths[] = count(
                $processor->words(
                    $sentence,
                    $language
                )
            );
        }

        $shortest = min($lengths);
        $longest = max($lengths);
        $average = array_sum($lengths) / $total;

        $spread = $longest - $shortest;
        $variety = min(
            100,
            (int) round($spread * 5)
        );

        return new SentenceReport(
            total: $total,
            shortest: $shortest,
            longest: $longest,
            average: $average,
            variety: $variety,
        );
    }
}
