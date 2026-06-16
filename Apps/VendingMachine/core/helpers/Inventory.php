<?php

namespace Apps\VendingMachine\Core\Helpers;

class Inventory
{
    protected array $productCatalog;

    public function __construct(array $productCatalog) {
        $this->productCatalog = $productCatalog;
    }

    public function exists(string $product): bool {
        return isset($this->productCatalog[$product]);
    }

    public function getPrice(string $product): float {
        return $this->productCatalog[$product];
    }

    /**
     * return <string, float>[]
     */
    public function getProductCatalog(): array {
        return $this->productCatalog;
    }
}
