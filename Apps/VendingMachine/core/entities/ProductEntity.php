<?php

namespace Apps\VendingMachine\Core\Entities;

class ProductEntity
{
    public int $id;
    public string $name;
    public float $price;
    public string $priceLbl;

    public function __construct(int $id, string $name, float $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function setPriceLbl(string $lbl) {
        $this->priceLbl = $lbl;
    }

    public function __set(string $name, mixed $value) {}
}
