<?php

namespace Apps\AdvertisingBidAuction\Core\Entities;

class CSVRow
{
    public int $id;
    public string $bid;

    public function __construct(int $id, string $bid) {
        $this->id = $id;
        $this->bid = $bid;
    }

    public function fillWith(CSVRow $CSVRow) {
        $this->id = $CSVRow->id;
        $this->bid = $CSVRow->bid;
    }

    public function __set(string $name, mixed $value) {}
}
