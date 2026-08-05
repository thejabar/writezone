<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Analysis\GrammarReport;
use App\Intelligence\Analysis\SentenceReport;
use App\Intelligence\Analysis\VocabularyReport;
use App\Intelligence\Analysis\ReadabilityReport;
use App\Intelligence\Evaluation\QualityResult;
use App\Intelligence\Grammar\Diagnostics\GrammarDiagnostics;
use App\Intelligence\Grammar\GrammarFeedback;
use App\Intelligence\Summary\ExecutiveSummary;

final class LabResult
{
    public function __construct(

        private readonly AnalysisReport $report,

    ) {
    }

    public function report(): AnalysisReport
    {
        return $this->report;
    }

    public function metrics(): ContentMetrics
    {
        return $this->report->metrics();
    }

    public function quality(): QualityResult
    {
        return $this->report->quality();
    }

    public function sentences(): SentenceReport
    {
        return $this->report->sentences();
    }

    public function vocabulary(): VocabularyReport
    {
        return $this->report->vocabulary();
    }
    
    public function readability(): ReadabilityReport
{
    return $this->report->readability();
}

    public function grammar(): GrammarReport
    {
        return $this->report->grammar();
    }

    public function grammarFeedback(): GrammarFeedback
    {
        return $this->report->grammarFeedback();
    }

    public function grammarDiagnostics(): GrammarDiagnostics
    {
        return $this->report->grammarDiagnostics();
    }

    public function executiveSummary(): ?ExecutiveSummary
    {
        return $this->report->executiveSummary();
    }

    public function metadata(): AnalysisMetadata
    {
        return $this->report->metadata();
    }

    public function score(): float
    {
        return $this->quality()->score();
    }
}