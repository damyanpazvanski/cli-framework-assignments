<?php

namespace CommonF\Files;

use CommonF\Interfaces\IDataStreamAdapter;

abstract class FileStreamAbstract implements IDataStreamAdapter {
    protected $stream;

    public function __construct(string $path) {
        if (!file_exists($path)) {
            throw new \Exception("File not found: $path");
        }
	}

    public function getStream() {
        return $this->stream;
    }

    public function openStream(string $filePath, string $mode = 'rb') {}

    public function close(): void {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }
}
