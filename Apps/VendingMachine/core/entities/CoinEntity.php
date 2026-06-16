<?php

namespace Apps\VendingMachine\Core\Entities;

class CoinEntity
{
    public int $id;
    public float $price;
    public string $priceLbl;

    public function __construct(int $id, float $price) {
        $this->id = $id;
        $this->price = $price;
    }

    public function setPriceLbl(string $lbl) {
        $this->priceLbl = $lbl;
    }

    public function __set(string $name, mixed $value) {}
}
