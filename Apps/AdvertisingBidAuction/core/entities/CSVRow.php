<?php

namespace Apps\AdvertisingBidAuction\Core\Entities;

class CSVRow
{
    protected $id;
    protected $bid;

    public function __construct(int $id, double $bid) {
		$this->id = $id;
		$this->bid = $bid;
    }

    public function __get(string $prop) {
        return $this[$prop];
    }
}
