<?php

namespace Apps\AdvertisingBidAuction\Core\Files;

use Common\Files\FileStream;

class CsvFile extends FileStream
{
    protected array $headers;

    public function __construct() {}

    public function getHeaders() {
        $this->headers ?? $this->setHeaders();

        return $this->headers;
    }

    protected function setHeaders() {
        rewind($this->stream);

        $headers = fgetcsv($this->stream);

        if ($headers == false) {
            throw new \Esception('Wrong File Headers');
        } else if (empty(array_filter($headers, 'trim'))) {
            throw new \Esception('Empty File Headers');
        }

        $this->headers = array_map('trim', $headers);
    }
}
