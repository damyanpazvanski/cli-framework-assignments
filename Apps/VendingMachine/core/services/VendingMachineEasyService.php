<?php

namespace Apps\VendingMachine\Core\Services;

use Apps\VendingMachine\Core\Validators\VendingMachineEasyValidator;
use Apps\VendingMachine\Core\Helpers\CurrencyFormatter;
use Apps\VendingMachine\Core\Helpers\Inventory;
use Apps\VendingMachine\Core\Loggers\CLIVendingLogger;

class VendingMachineEasyService
{
    protected VendingMachineEasyValidator $vendingMachineEasyValidator;
    protected CurrencyFormatter $currencyFormatter;
    protected CLIVendingLogger $CLIVendingLogger;
    protected Inventory $inventory;
    protected float $availableMoney = 0.0;
    protected array $formatedCoinsStrs = [];

    public function __construct(CurrencyFormatter $currencyFormatter, Inventory $inventory, CLIVendingLogger $CLIVendingLogger) {
        $this->currencyFormatter = $currencyFormatter;
        $this->inventory = $inventory;
        $this->CLIVendingLogger = $CLIVendingLogger;
    }

    public function viewDrinks(): self {
        $message = 'Напитки:' . PHP_EOL;

        foreach ($this->inventory->getProductCatalog() as $product => $price) {
            $message .= $product . ': ' . $this->currencyFormatter->formatCurrency($price) . PHP_EOL;
        }

        $this->CLIVendingLogger->log($message);

        return $this;
    }

    public function putCoin($coin): self {
        if (!$this->vendingMachineEasyValidator->validateCoin($coin)) {
            $this->CLIVendingLogger->error($this->vendingMachineEasyValidator->getWrongCoinMsg($this->formatedCoinsStrs));

            return $this;
        }

        $this->availableMoney += $coin;

        $this->CLIVendingLogger->success(
            $this->vendingMachineEasyValidator->getSuccessullyPasteCoinMsg(
                $this->currencyFormatter->formatCurrency($coin),
                $this->currencyFormatter->formatCurrency($this->availableMoney)
            )
        );

        return $this;
    }

    public function getCoins(): self {
        if ($this->availableMoney == 0) {
            $this->CLIVendingLogger->error($this->vendingMachineEasyValidator->getNoMoreMoneyLeftMsg());

            return $this;
        }

        $sortedCoins = $this->vendingMachineEasyValidator->getAllPossibleCoins();
        rsort($sortedCoins, SORT_NUMERIC);

        /**
         * $coins<string, int>
         */
        $coins = [];
        $fullAmount = $this->availableMoney;
        $over = 0;
        foreach ($sortedCoins as $coin) {
            // Casting is required because floor is not enough sensitive to decimals
            $cCount = floor((string) ($this->availableMoney / $coin));

            if ($cCount > 0) {
                $this->availableMoney -= $cCount * $coin;
                $coins[$this->currencyFormatter->formatCurrency($coin)] = $cCount;

                if ($this->availableMoney == 0) {
                    break;
                }
            }
        }

        $this->availableMoney = 0;

        $this->CLIVendingLogger->success($this->vendingMachineEasyValidator->getSuccessullyTakenCoinsMsg($fullAmount, $coins));

        return $this;
    }

    public function buyDrink(string $product): self {
        if (!$this->inventory->exists($product)) {
            $this->CLIVendingLogger->error($this->vendingMachineEasyValidator->getWrongProductMsg());
            return $this;
        }

        $productPrice = $this->inventory->getPrice($product);

        if ($this->availableMoney < $productPrice) {
            $this->CLIVendingLogger->error($this->vendingMachineEasyValidator->getNotEnoughMoneyMsg());
            return $this;
        }

        $this->availableMoney -= $productPrice;

        $this->CLIVendingLogger->success($this->vendingMachineEasyValidator->getSuccessullyBoughtMsg(
            $product,
            $this->currencyFormatter->formatCurrency($productPrice),
            $this->currencyFormatter->formatCurrency($this->availableMoney)
        ));

        return $this;
    }

    public function viewAmount(): self {
        $this->CLIVendingLogger->log($this->vendingMachineEasyValidator->getViewAmountMsg(
            $this->currencyFormatter->formatCurrency($this->availableMoney)
        ));

        return $this;
    }

    public function setValidator(VendingMachineEasyValidator $vendingMachineEasyValidator) {
        $this->vendingMachineEasyValidator = $vendingMachineEasyValidator;

        $this->vendingMachineEasyValidator->setInventory($this->inventory);
        $this->vendingMachineEasyValidator->setCurrencyFormatter($this->currencyFormatter);
        $this->formatAllPossibleCoins();
    }

    private function formatAllPossibleCoins() {
        foreach ($this->vendingMachineEasyValidator->getAllPossibleCoins() as $coin) {
            $this->formatedCoinsStrs[] = $this->currencyFormatter->formatCurrency($coin);
        }
    }
}
