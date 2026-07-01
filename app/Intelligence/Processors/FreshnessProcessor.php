<?php

declare(strict_types=1);

namespace App\Intelligence\Processors;

use App\Engines\Contracts\Processor;
use App\Feed\FeedCandidate;
use App\Intelligence\Signals\FreshnessSignal;
use DateTimeImmutable;

final class FreshnessProcessor implements Processor
{
    /**
     * @param FeedCandidate[] $data
     * @return FeedCandidate[]
     */
    public function process(
        array $data
    ): array {

        $now = new DateTimeImmutable();

        foreach ($data as $candidate) {

            if (! $candidate instanceof FeedCandidate) {
                continue;
            }

            $published = new DateTimeImmutable(
                $candidate->item->created_at
            );

            $ageHours = (
                $now->getTimestamp()
                - $published->getTimestamp()
            ) / 3600;

            [$score, $reason] = match (true) {

                $ageHours <= 1 => [
                    30.0,
                    'Published within the last hour.',
                ],

                $ageHours <= 6 => [
                    25.0,
                    'Published within the last 6 hours.',
                ],

                $ageHours <= 24 => [
                    20.0,
                    'Published today.',
                ],

                $ageHours <= 72 => [
                    15.0,
                    'Published within the last 3 days.',
                ],

                $ageHours <= 168 => [
                    10.0,
                    'Published within the last week.',
                ],

                default => [
                    5.0,
                    'Published more than a week ago.',
                ],

            };

            $candidate->signals->add(
                new FreshnessSignal(
                    value: $score,
                    reason: $reason,
                    ageHours: round(
                        $ageHours,
                        2
                    )
                )
            );
        }

        return $data;
    }
}