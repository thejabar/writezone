<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Analysis\ContentMetrics;
use App\Intelligence\Evaluation\QualityResult;
use App\Intelligence\Analysis\SentenceReport;

final class AnalysisReport
{
    public function __construct(
        private readonly ContentMetrics $metrics,
        private readonly QualityResult $quality,
        private readonly SentenceReport $sentences,
        private readonly AnalysisMetadata $metadata,
    ) {
    }

    /**
     * Content metrics collected during analysis.
     */
    public function metrics(): ContentMetrics
    {
        return $this->metrics;
    }

    /**
     * Overall writing quality.
     */
    public function quality(): QualityResult
    {
        return $this->quality;
    }
    
    /**
 * Sentence intelligence.
 */
public function sentences(): SentenceReport
{
    return $this->sentences;
}

    /**
     * Analysis metadata.
     */
    public function metadata(): AnalysisMetadata
    {
        return $this->metadata;
    }

    /**
     * Convenience helper.
     */
    public function score(): float
    {
        return $this->quality->score();
    }
}
