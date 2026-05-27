<?php

namespace Common\Interfaces;

use Common\Interfaces\IDataStreamAdapter;

interface IRepository
{
    public function __construct(IDataStreamAdapter $fileStream);
    public function loadStream(string $filePath, string $mode);
}
