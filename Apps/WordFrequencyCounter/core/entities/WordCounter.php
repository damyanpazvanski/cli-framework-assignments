<?php

namespace Apps\WordFrequencyCounter\Core\Entities;

class WordCounter
{
    public int $id;
    public string $word;
    public int $frequency;

    public function __construct(int $id, string $word, string $frequency) {
        $this->id = $id;
        $this->word = $word;
        $this->frequency = $frequency;
    }

    public function __set(string $name, mixed $value) {}
}
