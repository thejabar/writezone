<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluation;

use App\Support\Collections\ArrayCollection;

final class EvaluationCollection extends ArrayCollection
{
    public function add(
        EvaluationResult $evaluation
    ): void {
        $this->items[] = $evaluation;
    }

    public function get(
        string $name
    ): ?EvaluationResult {

        foreach ($this->items as $evaluation) {

            if (
                $evaluation->name() === $name
            ) {
                return $evaluation;
            }

        }

        return null;
    }

    /**
     * @return EvaluationResult[]
     */
    public function all(): array
    {
        return $this->items;
    }
}