<?php

namespace Common\Repositories;

use Common\Interfaces\IRepository;
use Common\Interfaces\IDataStreamAdapter;

class FileRepositoryAbstract implements IRepository
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
