<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

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
            '/\R{2,}/',
            trim($content)
        );

        $sentences = preg_split(
            '/(?<=[.!?])\s+/',
            trim($content)
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

        return new ContentMetrics(
            characters: $characters,
            words: $words,
            sentences: count(
                array_filter($sentences)
            ),
            paragraphs: count(
                array_filter($paragraphs)
            ),
            mentions: count(
                $mentions[0]
            ),
            hashtags: count(
                $hashtags[0]
            ),
            links: count(
                $links[0]
            ),
            emojis: count(
                $emojis[0]
            )
        );
    }
}
