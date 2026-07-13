<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

final class SentenceReport
{
    public function __construct(

        public readonly int $total,

        public readonly int $shortest,

        public readonly int $longest,

        public readonly float $average,

        public readonly int $variety,

    ) {
    }
}