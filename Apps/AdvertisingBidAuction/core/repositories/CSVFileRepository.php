<?php

namespace Apps\AdvertisingBidAuction\Core\Repositories;

use CommonF\Repositories\LocalFileRepository;

class CSVFileRepository extends LocalFileRepository
{
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

    public function getRows(): \Generator {
        rewind($this->fileStream->getStream());

        fgets($this->fileStream->getStream());  // Skip Headers

        while (($row = fgetcsv($this->fileStream->getStream())) !== false) {
            yield $row;
        }
    }
}
