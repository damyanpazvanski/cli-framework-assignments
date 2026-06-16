<?php

namespace Apps\VendingMachine\Core\Structures;

/**
 * Circular Queue
 */
class RollingBacklog {
    private array $buffer;
    private int $capacity;
    private int $size;
    private int $head; // Points to the oldest item
    private int $tail; // Points to the next write slot

    public function __construct(array $config = []) {
        $this->capacity = $config['keep'] ?? 10;
        $this->buffer = array_fill(0, $capacity, null);
        $this->size = 0;
        $this->head = 0;
        $this->tail = 0;
    }

    public function add(string $record): void {
        $this->buffer[$this->tail] = $record;

        // Add a record, overwriting the oldest if it is full
        if ($this->size === $this->capacity) {
            $this->head = ($this->head + 1) % $this->capacity;
        } else {
            $this->size++;
        }

        $this->tail = ($this->tail + 1) % $this->capacity;
    }

    // Retrieve all active records from oldest to newest
    public function getHistory(): array {
        $history = [];

        for ($i = 0; $i < $this->size; $i++) {
            $index = ($this->head + $i) % $this->capacity;
            $history[] = $this->buffer[$index];
        }

        return $history;
    }
}