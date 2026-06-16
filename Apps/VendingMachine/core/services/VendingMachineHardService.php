<?php

namespace Apps\VendingMachine\Core\Services;

use Apps\VendingMachine\Core\Validators\VendingMachineMediumValidator;
use Apps\VendingMachine\Core\Repositories\ProductRepository;
use Apps\VendingMachine\Core\Repositories\CoinRepository;
use Apps\VendingMachine\Core\Helpers\Wallet;
use Apps\VendingMachine\Core\Loggers\DisplayLogger;
use Apps\VendingMachine\Core\DTO\VendingMachingeJSONResponse;

class VendingMachineHardService
{
    protected VendingMachineMediumValidator $vendingMachineMediumValidator;
    protected ProductRepository $productRepository;
    protected CoinRepository $coinRepository;
    protected Wallet $wallet;
    protected DisplayLogger $displayLogger;
    protected array $allPossibleCoins = [];

    public function __construct(ProductRepository $productRepository, CoinRepository $coinRepository, Wallet $wallet, DisplayLogger $displayLogger) {
        $this->productRepository = $productRepository;
        $this->coinRepository = $coinRepository;
        $this->wallet = $wallet;
        $this->displayLogger = $displayLogger;
    }

    public function getPossibleCoins($limit = 100) {
        $coins = $this->coinRepository->getAll(1, $limit);

        array_map(fn($coin) => $coin->setPriceLbl(
            $this->displayLogger->getCurrencyFormatter()->formatCurrency($coin->price)
        ), $coins);

        return $coins;
    }

    public function getPossibleProducts($limit = 100) {
        $products = $this->productRepository->getAll(1, $limit);

        array_map(fn($product) => $product->setPriceLbl(
            $this->displayLogger->getCurrencyFormatter()->formatCurrency($product->price)
        ), $products);

        return $products;
    }

    public function putCoin($coin, float $fullAmount) {
        if (!$this->vendingMachineMediumValidator->validateCoin($coin, $this->allPossibleCoins)) {
            return VendingMachingeJSONResponse::build($this->displayLogger->getWrongCoinMsg(), false);
        }

        return VendingMachingeJSONResponse::build($this->displayLogger->getSuccessullyPasteCoinMsg($coin, $fullAmount + $coin));
    }

    public function insertProduct(array $product) {
        $name = $product['name'];
        $price = $product['price'];

        if (!$this->vendingMachineMediumValidator->validateMoneyAmount($price)) {
            return VendingMachingeJSONResponse::build($this->displayLogger->getWrongPriceMsg(), false);
        } else if (empty($name)) {
            return VendingMachingeJSONResponse::build($this->displayLogger->getWrongStrMsg('name'), false);
        }
        
        if ($this->productRepository->existsByName($name)) {
            return VendingMachingeJSONResponse::build($this->displayLogger->getDuplicatedMsg(), false);
        }

        $product = $this->productRepository->insert(compact('name', 'price'));

        $product->setPriceLbl($this->displayLogger->getCurrencyFormatter()->formatCurrency($price));

        return VendingMachingeJSONResponse::build($this->displayLogger->getInsertedProductMsg(), true, $product);
    }

    public function deleteProduct($id) {
        $this->productRepository->delete($id);

        return VendingMachingeJSONResponse::build($this->displayLogger->getDeletedProductMsg());
    }

    public function insertCoin(array $product) {
        $price = $product['price'];

        if (!$this->vendingMachineMediumValidator->validateMoneyAmount($price) || $price == 0) {
            return VendingMachingeJSONResponse::build($this->displayLogger->getWrongPriceMsg(), false);
        }

        if ($this->coinRepository->existsByPrice($price)) {
            return VendingMachingeJSONResponse::build($this->displayLogger->getDuplicatedMsg(), false);
        }

        $coin = $this->coinRepository->insert(compact('price'));

        $coin->setPriceLbl($this->displayLogger->getCurrencyFormatter()->formatCurrency($price));

        return VendingMachingeJSONResponse::build($this->displayLogger->getInsertedCoinMsg(), true, $coin);
    }

    public function deleteCoin($id) {
        $this->coinRepository->delete($id);

        return VendingMachingeJSONResponse::build($this->displayLogger->getDeletedCoinMsg());
    }

    public function getCoins(float $fullAmount) {
        if ($fullAmount == 0) {
            return VendingMachingeJSONResponse::build($this->displayLogger->getNoMoreMoneyLeftMsg(), false);
        }

        $this->wallet->set($fullAmount);

        $coins = $this->wallet->getLeftAmountAsCoins($this->allPossibleCoins);

        $this->wallet->set(0);

        return VendingMachingeJSONResponse::build($this->displayLogger->getSuccessullyTakenCoinsMsg($fullAmount, $coins));
    }

    public function buyDrink(string $productName, float $moneyAmount) {
        $product = $this->productRepository->getByName($productName);

        if (!$product) {
            return VendingMachingeJSONResponse::build($this->displayLogger->getWrongProductMsg(), false);
        }

        if ($moneyAmount < $product->price) {
            return VendingMachingeJSONResponse::build($this->displayLogger->getNotEnoughMoneyMsg(), false);
        }

        return VendingMachingeJSONResponse::build($this->displayLogger->getSuccessullyBoughtMsg($product->name, $product->price, $moneyAmount - $product->price));
    }

    public function viewAmount(float $moneyAmount) {
        return VendingMachingeJSONResponse::build($this->displayLogger->getViewAmountMsg($moneyAmount));
    }

    public function display(): DisplayLogger {
        return $this->displayLogger;
    }

    public function setValidator(VendingMachineMediumValidator $vendingMachineMediumValidator) {
        $this->vendingMachineMediumValidator = $vendingMachineMediumValidator;
    }

    public function setDbConfig(array $dbConfig) {
        $this->productRepository->setDbConfig($dbConfig);
        $this->coinRepository->setDbConfig($dbConfig);

        $this->allPossibleCoins = array_column($this->coinRepository->getAll(1, 100), 'price');
        $this->displayLogger->keepFormatedAllPossibleCoins($this->allPossibleCoins);
    }
}
