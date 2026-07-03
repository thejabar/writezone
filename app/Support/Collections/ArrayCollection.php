<?php

declare(strict_types=1);

namespace App\Support\Collections;

use Countable;
use IteratorAggregate;
use ArrayIterator;
use Traversable;

final class ArrayCollection implements
    Collection,
    Countable,
    IteratorAggregate
{
    /**
     * @param array<mixed> $items
     */
    public function __construct(
        private readonly array $items = []
    ) {
    }

    /**
     * Return all items.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Number of items.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Is the collection empty?
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Return the first item.
     */
    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }

    /**
     * Return the last item.
     */
    public function last(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->items[array_key_last($this->items)];
    }

    /**
     * Allow foreach().
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator(
            $this->items
        );
    }
}
