<?php

namespace Apps\EscapeALabyrinth\Core\Structures;

class FIFOQueue
{
    private int $head = 0;
    private int $tail = 0;
    private array $data = [];

    public function enqueue($item) {
        $this->data[$this->tail] = $item;
        $this->tail++;
    }

    public function dequeue() {
        if ($this->isEmpty()) {
            return null;
        }
        
        $item = $this->data[$this->head];
        
        unset($this->data[$this->head]);
        
        $this->head++;

        return $item;
    }

    public function isEmpty() {
        return $this->head === $this->tail;
    }
}
