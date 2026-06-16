<?php

namespace Apps\VendingMachine\Core\Services;

use Apps\VendingMachine\Core\Validators\VendingMachineMediumValidator;
use Apps\VendingMachine\Core\Helpers\Inventory;
use Apps\VendingMachine\Core\Helpers\Wallet;
use Apps\VendingMachine\Core\Loggers\DisplayLogger;

class VendingMachineMediumService
{
    protected VendingMachineMediumValidator $vendingMachineMediumValidator;
    protected Inventory $inventory;
    protected Wallet $wallet;
    protected DisplayLogger $displayLogger;

    public function __construct(Inventory $inventory, Wallet $wallet, DisplayLogger $displayLogger) {
        $this->inventory = $inventory;
        $this->wallet = $wallet;
        $this->displayLogger = $displayLogger;
    }

    public function viewDrinks(): self {
        $message = $this->displayLogger->getViewDrinksMsg($this->inventory->getProductCatalog());

        $this->displayLogger->log($message);

        return $this;
    }

    public function putCoin($coin): self {
        if (!$this->vendingMachineMediumValidator->validateCoin($coin)) {
            $this->displayLogger->error($this->displayLogger->getWrongCoinMsg());
            return $this;
        }

        $this->wallet->add($coin);

        $this->displayLogger->success(
            $this->displayLogger->getSuccessullyPasteCoinMsg($coin, $this->wallet->getAmount())
        );

        return $this;
    }

    public function getCoins(): self {
        if ($this->wallet->getAmount() == 0) {
            $this->displayLogger->error($this->displayLogger->getNoMoreMoneyLeftMsg());

            return $this;
        }

        $fullAmount = $this->wallet->getAmount();
        $coins = $this->wallet->getLeftAmountAsCoins($this->vendingMachineMediumValidator->getAllPossibleCoins());

        $this->wallet->set(0);

        $this->displayLogger->success($this->displayLogger->getSuccessullyTakenCoinsMsg($fullAmount, $coins));

        return $this;
    }

    public function buyDrink(string $product): self {
        if (!$this->inventory->exists($product)) {
            $this->displayLogger->error($this->displayLogger->getWrongProductMsg());
            return $this;
        }

        $productPrice = $this->inventory->getPrice($product);

        if ($this->wallet->getAmount() < $productPrice) {
            $this->displayLogger->error($this->displayLogger->getNotEnoughMoneyMsg());
            return $this;
        }

        $this->wallet->substract($productPrice);

        $this->displayLogger->success($this->displayLogger->getSuccessullyBoughtMsg(
            $product, $productPrice, $this->wallet->getAmount()
        ));

        return $this;
    }

    public function viewAmount(): self {
        $this->displayLogger->log($this->displayLogger->getViewAmountMsg($this->wallet->getAmount()));

        return $this;
    }

    public function display(): DisplayLogger {
        return $this->displayLogger;
    }

    public function setValidator(VendingMachineMediumValidator $vendingMachineMediumValidator) {
        $this->vendingMachineMediumValidator = $vendingMachineMediumValidator;

        $this->vendingMachineMediumValidator->setInventory($this->inventory);
        $this->vendingMachineMediumValidator->setWallet($this->wallet);

        $this->displayLogger->keepFormatedAllPossibleCoins($this->vendingMachineMediumValidator->getAllPossibleCoins());
    }
}
