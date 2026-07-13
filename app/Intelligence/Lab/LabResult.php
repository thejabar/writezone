<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Analysis\SentenceReport;
use App\Intelligence\Evaluation\QualityResult;

final class LabResult
{
    public function __construct(
        private readonly AnalysisReport $report,
    ) {
    }

    /**
     * Complete analysis report.
     */
    public function report(): AnalysisReport
    {
        return $this->report;
    }

    /**
     * Content metrics.
     */
    public function metrics(): ContentMetrics
    {
        return $this->report->metrics();
    }

    /**
     * Quality assessment.
     */
    public function quality(): QualityResult
    {
        return $this->report->quality();
    }
    
    /**
 * Sentence intelligence.
 */
public function sentences(): SentenceReport
{
    return $this->report->sentences();
}

    /**
     * Analysis metadata.
     */
    public function metadata(): AnalysisMetadata
    {
        return $this->report->metadata();
    }

    /**
     * Overall score.
     */
    public function score(): float
    {
        return $this->report->score();
    }
}