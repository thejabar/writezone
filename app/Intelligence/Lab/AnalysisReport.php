<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Analysis\GrammarReport;
use App\Intelligence\Analysis\SentenceReport;
use App\Intelligence\Analysis\VocabularyReport;
use App\Intelligence\Evaluation\QualityResult;
use App\Intelligence\Grammar\Diagnostics\GrammarDiagnostics;
use App\Intelligence\Grammar\GrammarFeedback;
use App\Intelligence\Summary\ExecutiveSummary;
use App\Intelligence\Analysis\ReadabilityReport;

final class AnalysisReport
{
    public function __construct(

        private readonly ContentMetrics $metrics,

        private readonly QualityResult $quality,

        private readonly SentenceReport $sentences,

        private readonly VocabularyReport $vocabulary,
        
        private readonly ReadabilityReport $readability,

        private readonly GrammarReport $grammar,

        private readonly GrammarFeedback $grammarFeedback,

        private readonly GrammarDiagnostics $grammarDiagnostics,

        private readonly ?ExecutiveSummary $executiveSummary,

        private readonly AnalysisMetadata $metadata,

    ) {
    }

    public function metrics(): ContentMetrics
    {
        return $this->metrics;
    }

    public function quality(): QualityResult
    {
        return $this->quality;
    }

    public function sentences(): SentenceReport
    {
        return $this->sentences;
    }

    public function vocabulary(): VocabularyReport
    {
        return $this->vocabulary;
    }
    
    public function readability(): ReadabilityReport
{
    return $this->readability;
}

    public function grammar(): GrammarReport
    {
        return $this->grammar;
    }

    public function grammarFeedback(): GrammarFeedback
    {
        return $this->grammarFeedback;
    }

    public function grammarDiagnostics(): GrammarDiagnostics
    {
        return $this->grammarDiagnostics;
    }

    public function executiveSummary(): ?ExecutiveSummary
    {
        return $this->executiveSummary;
    }

    public function metadata(): AnalysisMetadata
    {
        return $this->metadata;
    }
}