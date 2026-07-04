<?php

declare(strict_types=1);

namespace App\Intelligence\Benchmarks;

use App\Support\Collections\ArrayCollection;

final class BenchmarkSuite
{
    public function cases(): ArrayCollection
    {
        return new ArrayCollection([

            new BenchmarkCase(
                name: 'Excellent Article',
                content: <<<TEXT
Today I explored how Renaissance thinking continues to influence modern innovation.

One interesting observation was that great ideas often emerge when people connect knowledge from different disciplines.
TEXT,
                expectations: [
                    'quality' => 'high',
                    'spam' => 'low',
                ]
            ),

            new BenchmarkCase(
                name: 'Spam',
                content: <<<TEXT
BUY NOW!!!!

BUY NOW!!!!

https://spam.com

#sale #sale #sale #sale #sale
TEXT,
                expectations: [
                    'quality' => 'low',
                    'spam' => 'high',
                ]
            ),

            new BenchmarkCase(
                name: 'Question',
                content: <<<TEXT
Has anyone used Laravel Octane in production?
TEXT,
                expectations: [
                    'quality' => 'medium',
                    'spam' => 'low',
                ]
            ),

        ]);
    }
}
