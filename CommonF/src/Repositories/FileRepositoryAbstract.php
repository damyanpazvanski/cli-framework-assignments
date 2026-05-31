<?php

namespace CommonF\Repositories;

use CommonF\Interfaces\IFileRepository;
use CommonF\Interfaces\IDataStreamAdapter;

class FileRepositoryAbstract implements IFileRepository
{
    public IDataStreamAdapter $fileStream;

    public function __construct(IDataStreamAdapter $fileStream) {
        $this->fileStream = $fileStream;
    }

    public function loadStream(string $filePath, string $mode) {
        $this->fileStream->openStream($filePath, $mode);
    }

    public function getFileStream(): IDataStreamAdapter {
        return $this->fileStream;
    }
}
