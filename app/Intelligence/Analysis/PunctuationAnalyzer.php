<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

final class PunctuationAnalyzer
{
    public function analyze(
        string $content
    ): PunctuationReport {

        $periods = substr_count(
            $content,
            '.'
        );

        $commas = substr_count(
            $content,
            ','
        );

        $questionMarks = substr_count(
            $content,
            '?'
        );

        $exclamationMarks = substr_count(
            $content,
            '!'
        );

        $semicolons = substr_count(
            $content,
            ';'
        );

        $colons = substr_count(
            $content,
            ':'
        );

        $quotationMarks =
            substr_count($content, '"')
            + substr_count($content, "'");

        $parentheses =
            substr_count($content, '(')
            + substr_count($content, ')');

        $squareBrackets =
            substr_count($content, '[')
            + substr_count($content, ']');

        $curlyBraces =
            substr_count($content, '{')
            + substr_count($content, '}');

        $ellipses = preg_match_all(
            '/\.{3,}/u',
            $content
        );

        $repeatedPunctuation = preg_match_all(
            '/([!?.,;:])\1+/u',
            $content
        );

        /*
        |--------------------------------------------------------------------------
        | Balanced punctuation
        |--------------------------------------------------------------------------
        */

        $balancedQuotes =
            $quotationMarks % 2 === 0;

        $balancedParentheses =
            substr_count($content, '(')
            === substr_count($content, ')');

        $balancedSquareBrackets =
            substr_count($content, '[')
            === substr_count($content, ']');

        $balancedCurlyBraces =
            substr_count($content, '{')
            === substr_count($content, '}');

        /*
        |--------------------------------------------------------------------------
        | Density
        |--------------------------------------------------------------------------
        */

        $characters = max(
            1,
            mb_strlen($content)
        );

        $punctuationDensity = round(

            (
                $periods +
                $commas +
                $questionMarks +
                $exclamationMarks +
                $semicolons +
                $colons
            ) / $characters * 100,

            2

        );

        return new PunctuationReport(

            periods: $periods,

            commas: $commas,

            questionMarks: $questionMarks,

            exclamationMarks: $exclamationMarks,

            semicolons: $semicolons,

            colons: $colons,

            quotationMarks: $quotationMarks,

            parentheses: $parentheses,

            squareBrackets: $squareBrackets,

            curlyBraces: $curlyBraces,

            ellipses: $ellipses,

            repeatedPunctuation:
                $repeatedPunctuation,

            balancedQuotes:
                $balancedQuotes,

            balancedParentheses:
                $balancedParentheses,

            balancedSquareBrackets:
                $balancedSquareBrackets,

            balancedCurlyBraces:
                $balancedCurlyBraces,

            punctuationDensity:
                $punctuationDensity,

        );

    }
}