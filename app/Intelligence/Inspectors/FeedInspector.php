<?php

declare(strict_types=1);

namespace App\Intelligence\Inspectors;

use App\Ranking\Results\RankedCandidate;

final class FeedInspector
{
    public function inspect(
        RankedCandidate $candidate
    ): InspectionResult {

        $item = $candidate->item();

        $inspection = [

            new InspectionItem(
                'Author',
                $item->handle
            ),

            new InspectionItem(
                'Published',
                $item->created_at
            ),

            new InspectionItem(
                'Score',
                $candidate->score()
            ),

            new InspectionItem(
                'Confidence',
                $candidate->confidence()
            ),

        ];

        foreach (
            $candidate->signals()->all()
            as $signal
        ) {

            $inspection[] = new InspectionItem(
                $signal->name(),
                [
                    'value' => $signal->value(),
                    'reason' => $signal->reason(),
                ]
            );

        }

        return new InspectionResult(
            $inspection
        );

    }
}