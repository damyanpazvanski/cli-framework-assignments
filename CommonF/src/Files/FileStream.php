<?php

namespace CommonF\Files;

use CommonF\Files\FileStreamAbstract;

class FileStream extends FileStreamAbstract
{
    public function __construct(string $path, string $mode) {
		parent::__construct($path);

        $this->openStream($path, $mode);

        if (!$this->stream) {
            throw new \Exception("Failed to open stream: $path");
        }
    }

    public function openStream(string $filePath, string $mode = 'rb') {
        $this->stream = fopen($filePath, $mode);
    }
}
