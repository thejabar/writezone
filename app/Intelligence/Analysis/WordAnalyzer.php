<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

use App\Intelligence\Language\LanguageDefinition;
use App\Intelligence\Language\Text\LanguageTextProcessor;

final class WordAnalyzer
{
    public function analyze(
        string $content,
        ?LanguageDefinition $language = null
    ): WordReport {

        $language = $language
            ?? \App\Intelligence\Language\LanguageRegistry::default();

        $words = (new LanguageTextProcessor())
            ->words(
                $content,
                $language
            );

        $totalWords = count(
            $words
        );

        $frequency = array_count_values(
            $words
        );

        $uniqueWords = count(
            $frequency
        );

        $repeatedWords = max(
            0,
            $totalWords - $uniqueWords
        );

        $longWords = 0;

        $shortWords = 0;

        $totalLength = 0;

        foreach ($words as $word) {

            $length = mb_strlen(
                $word
            );

            $totalLength += $length;

            if ($length >= 8) {

                $longWords++;

            }

            if ($length <= 3) {

                $shortWords++;

            }

        }

        $averageWordLength =
            $totalWords > 0
                ? round(
                    $totalLength / $totalWords,
                    2
                )
                : 0.0;

        preg_match_all(
            '/\b\d+(?:\.\d+)?\b/u',
            $content,
            $numbers
        );

        preg_match_all(
            '/@\w+/u',
            $content,
            $mentions
        );

        preg_match_all(
            '/#\w+/u',
            $content,
            $hashtags
        );

        preg_match_all(
            '/https?:\/\/\S+/u',
            $content,
            $urls
        );

        preg_match_all(
            '/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/iu',
            $content,
            $emails
        );

        preg_match_all(
            '/\b[A-Z]{2,}\b/u',
            $content,
            $acronyms
        );

        arsort(
            $frequency
        );

        return new WordReport(

            totalWords:
                $totalWords,

            uniqueWords:
                $uniqueWords,

            repeatedWords:
                $repeatedWords,

            longWords:
                $longWords,

            shortWords:
                $shortWords,

            averageWordLength:
                $averageWordLength,

            numbers:
                count(
                    $numbers[0]
                ),

            mentions:
                count(
                    $mentions[0]
                ),

            hashtags:
                count(
                    $hashtags[0]
                ),

            urls:
                count(
                    $urls[0]
                ),

            emails:
                count(
                    $emails[0]
                ),

            acronyms:
                count(
                    $acronyms[0]
                ),

            wordFrequency:
                $frequency,

        );

    }
}
