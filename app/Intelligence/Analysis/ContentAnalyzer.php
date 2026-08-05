<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

use App\Intelligence\Support\SentenceParser;

final class ContentAnalyzer
{
    public function analyze(
        string $content
    ): ContentMetrics {

        $characters = mb_strlen(
            $content
        );

        $words = str_word_count(
            strip_tags($content)
        );

        $paragraphs = preg_split(
            '/\R{2,}/u',
            trim($content)
        );

        /*
        |--------------------------------------------------------------------------
        | Shared Sentence Parser
        |--------------------------------------------------------------------------
        */

        $sentences = SentenceParser::parse(
            $content
        );

        preg_match_all(
            '/@\w+/',
            $content,
            $mentions
        );

        preg_match_all(
            '/#\w+/',
            $content,
            $hashtags
        );

        preg_match_all(
            '/https?:\/\/\S+/',
            $content,
            $links
        );

        preg_match_all(
            '/[\x{1F300}-\x{1FAFF}]/u',
            $content,
            $emojis
        );

        /*
        |--------------------------------------------------------------------------
        | Advanced Metrics
        |--------------------------------------------------------------------------
        */

        $readingTime = max(
            1,
            (int) ceil(
                $words / 200
            )
        );

        $sentenceCount = count(
            $sentences
        );

        $paragraphCount = count(
            array_filter(
                $paragraphs
            )
        );

        $averageSentenceLength =
            $sentenceCount > 0
                ? round(
                    $words / $sentenceCount,
                    1
                )
                : 0.0;

        $averageParagraphLength =
            $paragraphCount > 0
                ? round(
                    $words / $paragraphCount,
                    1
                )
                : 0.0;

        preg_match_all(
            '/\?/',
            $content,
            $questions
        );

        preg_match_all(
            '/!/',
            $content,
            $exclamations
        );

        return new ContentMetrics(

            characters:
                $characters,

            words:
                $words,

            sentences:
                $sentenceCount,

            paragraphs:
                $paragraphCount,

            mentions:
                count(
                    $mentions[0]
                ),

            hashtags:
                count(
                    $hashtags[0]
                ),

            links:
                count(
                    $links[0]
                ),

            emojis:
                count(
                    $emojis[0]
                ),

            readingTime:
                $readingTime,

            averageSentenceLength:
                $averageSentenceLength,

            averageParagraphLength:
                $averageParagraphLength,

            questions:
                count(
                    $questions[0]
                ),

            exclamations:
                count(
                    $exclamations[0]
                ),

        );

    }
}