<?php

namespace CommonF\Interfaces;

use CommonF\Interfaces\IRepository;
use CommonF\Interfaces\IDataStreamAdapter;

interface IFileRepository extends IRepository
{
    public function __construct(IDataStreamAdapter $fileStream);
    public function loadStream(string $filePath, string $mode);
}
