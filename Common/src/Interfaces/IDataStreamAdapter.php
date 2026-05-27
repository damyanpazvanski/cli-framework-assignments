<?php

namespace Common\Interfaces;

interface IDataStreamAdapter
{
    public function openStream(string $filePath, string $mode = 'rb');
    public function getStream();
    public function close();
}
