<?php

declare(strict_types=1);

namespace App\Intelligence\Contracts;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Analysis\ReadabilityReport;
use App\Intelligence\Analysis\VocabularyReport;

interface Evaluator
{
    public function name(): string;

    public function evaluate(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): float;

    /**
     * @return string[]
     */
    public function strengths(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): array;

    /**
     * @return string[]
     */
    public function suggestions(
        ContentMetrics $metrics,
        ?VocabularyReport $vocabulary = null,
        ?ReadabilityReport $readability = null
    ): array;
}
