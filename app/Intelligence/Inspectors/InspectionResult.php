<?php

declare(strict_types=1);

namespace App\Intelligence\Inspectors;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class InspectionResult implements
    Countable,
    IteratorAggregate
{
    /**
     * @param InspectionItem[] $items
     */
    public function __construct(
        private readonly array $items = []
    ) {
    }

    /**
     * @return InspectionItem[]
     */
    public function items(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count(
            $this->items
        );
    }

    public function isEmpty(): bool
    {
        return empty(
            $this->items
        );
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator(
            $this->items
        );
    }
}