<?php

namespace Apps\AdvertisingBidAuction\Core\Repositories;

use Common\Repositories\LocalFileRepository;
use Common\Interfaces\IDataStreamAdapter;

class CSVFileRepository extends LocalFileRepository
{
    public function readAll(): array {
        $data = [];
        
        rewind($this->fileStream->getStream());

        while (($row = fgetcsv($this->fileStream->getStream(), 1000, ",")) !== false) {
            $data[] = $row;
        }

        return $data;
    }

    public function getHeadersWithIndicesDict(): array {
        $headersWithIndices = [];
        $headers = $this->fileStream->getHeaders();

        foreach ($headers as $ind => $header) {
            if ($header) {
                $headersWithIndices[$header] = $ind;
            }
        }

        return $headersWithIndices;
    }

}
