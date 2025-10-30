<?php

declare(strict_types=1);

namespace ShipMonk\Collections\LinkedList;

use Iterator;
use OutOfBoundsException;
use PHPUnit\Event\InvalidArgumentException;

/**
 * @implements Iterator<int, int|string>
 */
class SortedLinkedList implements Iterator
{
    private ?Node $head = null;
    private ?Type $type = null;
    private int $size = 0;

    private ?Node $current = null;
    private int $key = 0;

    /**
     * Inserts a value into the list
     *
     * @param int|string $value
     *
     * @return $this
     */
    public function insert(int|string $value): self
    {
        $type = $this->detectType($value);

        if ($this->type === null) {
            $this->type = $type;
        } elseif ($this->type !== $type) {
            throw new InvalidArgumentException(
                "Cannot insert $type->name value into a list of {$this->type->name} values"
            );
        }

        $newNode = new Node($value);

        //Empty node or value is a less existing head so add to the left
        if ($this->head === null || $value < $this->head->value) {
            $newNode->next = $this->head;
            $this->head = $newNode;
            $this->size++;
            return $this;
        }

        $current = $this->head;

        //Find the node to insert after based on the value
        while ($current->next !== null && $current->next->value < $value) {
            $current = $current->next;
        }

        //Insert after current node
        $newNode->next = $current->next;
        $current->next = $newNode;
        $this->size++;
        return $this;
    }

    /**
     * Returns the type of the list
     *
     * @return Type|null
     */
    public function getType(): ?Type
    {
        return $this->type;
    }

    /**
     * Removes a value from the list
     *
     * @param int|string $value
     *
     * @return bool
     */
    public function remove(int|string $value): bool
    {
        //Empty list
        if ($this->head === null) {
            return false;
        }

        //Head is the value to remove
        if ($this->head->value === $value) {
            $this->head = $this->head->next;
            $this->size--;
            return true;
        }

        //Find the node to remove
        $current = $this->head;
        while ($current->next !== null) {
            //Next node is the value to remove
            if ($current->next->value === $value) {
                //Remove the node
                $current->next = $current->next->next;
                $this->size--;
                return true;
            }
            //Next node is less than the value to remove
            $current = $current->next;
        }

        return false;
    }

    /**
     * Checks if the list contains a value
     *
     * @param int|string $value
     *
     * @return bool
     */
    public function contains(int|string $value): bool
    {
        $current = $this->head;
        while ($current !== null) {
            if ($current->value === $value) {
                return true;
            }
            if ($current->value > $value) {
                return false;
            }
            $current = $current->next;
        }
        return false;
    }

    /**
     * Returns the number of elements in the list
     *
     * @return int
     */
    public function count(): int
    {
        return $this->size;
    }

    /**
     * Returns the first element in the list
     *
     * @return int|string
     * @throws OutOfBoundsException
     */
    public function getFirst(): int|string
    {
        if ($this->head === null) {
            throw new OutOfBoundsException(
                "Cannot get first element from an empty list"
            );
        }

        return $this->head->value;
    }

    /**
     * Returns the last element in the list
     *
     * @return int|string
     * @throws OutOfBoundsException
     */
    public function getLast(): int|string
    {
        if ($this->head === null) {
            throw new OutOfBoundsException(
                "Cannot get last element from an empty list"
            );
        }

        return $this->get($this->size - 1);
    }

    /**
     * Returns the value at the specified index
     *
     * @param int $index
     *
     * @return int|string
     */
    public function get(int $index): int|string
    {
        if ($this->head === null || $index < 0 || $index >= $this->size) {
            throw new OutOfBoundsException(
                "Index $index is out of bounds for list of size {$this->size}"
            );
        }

        $current = $this->head;

        for ($i = 0; $i < $index; $i++) {
            $current = $current?->next;
        }

        if ($current === null) {
            return 0;
        }
        return $current->value;
    }

    /**
     * Checks if the list is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->head === null;
    }

    /**
     * Returns the list as an array
     *
     * @return array<int, int|string>
     */
    public function toArray(): array
    {
        $result = [];
        $current = $this->head;
        while ($current !== null) {
            $result[] = $current->value;
            $current = $current->next;
        }
        return $result;
    }

    /**
     * Clears the list
     *
     * @return void
     */
    public function clear(): void
    {
        $this->head = null;
        $this->type = null;
        $this->size = 0;
        $this->current = null;
        $this->key = 0;
    }

    /**
     * @return int|string|null
     */
    public function current(): null|int|string
    {
        return $this->current?->value;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->key;
    }

    /**
     * Moves the internal pointer to the next element in the list.
     *
     * @return void
     */
    public function next(): void
    {
        if ($this->current !== null) {
            $this->current = $this->current->next;
            $this->key++;
        }
    }

    /**
     * Resets the iterator to the beginning of the list.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->current = $this->head;
        $this->key = 0;
    }

    public function valid(): bool
    {
        return $this->current !== null;
    }

    private function detectType(int|string $value): Type
    {
        $getType = gettype($value);
        return match (true) {
            $getType === 'integer' => Type::INTEGER,
            $getType === 'string' => Type::STRING,
        };
    }
}
