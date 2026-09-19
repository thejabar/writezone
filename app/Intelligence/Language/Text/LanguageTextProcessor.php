<?php

declare(strict_types=1);

namespace App\Intelligence\Language\Text;

use App\Intelligence\Language\LanguageDefinition;

final class LanguageTextProcessor
{
    /**
     * @return string[]
     */
    public function words(
        string $content,
        LanguageDefinition $language
    ): array {
        $content = strip_tags($content);

        if (trim($content) === '') {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | Languages/scripts where whitespace is not a dependable word boundary
        |--------------------------------------------------------------------------
        |
        | Phase A2.6 deliberately provides a deterministic Unicode baseline.
        | Full linguistic segmentation for these scripts will be supplied by
        | dedicated language resources in later phases.
        |
        */

        if ($this->usesCharacterBaseline($language)) {
            preg_match_all(
                '/[\p{Han}\p{Hiragana}\p{Katakana}\p{Hangul}]+/u',
                $content,
                $matches
            );

            $words = [];

            foreach ($matches[0] as $segment) {
                $characters = preg_split(
                    '//u',
                    $segment,
                    -1,
                    PREG_SPLIT_NO_EMPTY
                );

                foreach ($characters as $character) {
                    $words[] = $character;
                }
            }

            return array_map(
                static fn (string $word): string => mb_strtolower($word),
                $words
            );
        }

        preg_match_all(
            '/\p{L}+(?:[\'’-]\p{L}+)?/u',
            $content,
            $matches
        );

        return array_map(
            static fn (string $word): string => mb_strtolower($word),
            $matches[0]
        );
    }

    private function usesCharacterBaseline(
        LanguageDefinition $language
    ): bool {
        return in_array(
            $language->script(),
            [
                'Han',
                'Japanese',
                'Hangul',
            ],
            true
        );
    }
}
