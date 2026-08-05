<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Analysis\PunctuationReport;
use App\Intelligence\Analysis\SentenceReport;
use App\Intelligence\Analysis\WordReport;

final class AnalysisContext
{
    public function __construct(

        private readonly string $content,

        private readonly ContentMetrics $metrics,

        private readonly SentenceReport $sentences,

        private readonly WordReport $words,

        private readonly PunctuationReport $punctuation,

    ) {
    }

    public function content(): string
    {
        return $this->content;
    }

    public function metrics(): ContentMetrics
    {
        return $this->metrics;
    }

    public function sentences(): SentenceReport
    {
        return $this->sentences;
    }

    public function words(): WordReport
    {
        return $this->words;
    }

    public function punctuation(): PunctuationReport
    {
        return $this->punctuation;
    }
}