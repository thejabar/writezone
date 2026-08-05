<?php

declare(strict_types=1);

namespace App\Intelligence\Summary;

final class ExecutiveSummary
{
    public function __construct(

        public readonly string $title,

        public readonly string $summary,

        public readonly string $overallAssessment,

    ) {
    }
}
