<?php

namespace Apps\VendingMachine\Core\Validators;

use CommonF\Validators\ValidatorAbstract;
use Apps\VendingMachine\Core\Helpers\CurrencyFormatter;
use Apps\VendingMachine\Core\Helpers\Inventory;

class VendingMachineEasyValidator extends ValidatorAbstract
{
    protected CurrencyFormatter $currencyFormatter;
    protected Inventory $inventory;
    protected array $formatedCoinsStrs = [];

    public function validate(): bool {
        // Validate Initial State
        return $this->validateConfigCoins() && $this->validateInventory();
    }

    public function getWrongProductMsg() {
        return 'Исканият продукт нe е намерен.';
    }

    public function getWrongCoinMsg() {
        return 'Автомата приема монети от: ' . implode(', ', $this->formatedCoinsStrs);
    }

    public function getNotEnoughMoneyMsg() {
        return 'Недостатъчна наличност.';
    }

    public function getNoMoreMoneyLeftMsg() {
        return 'Няма ресто за връщане.';
    }

    public function getViewAmountMsg(float $moneyLeft) {
        return 'Tекущата Ви сума е ' . $this->currencyFormatter->formatCurrency($moneyLeft);
    }

    public function getSuccessullyBoughtMsg(string $product, string $price, string $moneyLeft) {
        return 'Успешно закупихте ' . $product . ' от ' .
            $this->currencyFormatter->formatCurrency($price) . ', текущата Ви сума е ' .
            $this->currencyFormatter->formatCurrency($moneyLeft);
    }

    /**
     * $coins = [
     *  '0.05' => int quantity,
     * ]
     */
    public function getSuccessullyTakenCoinsMsg(string $fullAmount, array $coins) {
        $message = 'Получихте ресто ' . $this->currencyFormatter->formatCurrency($fullAmount) . ' в монети от: ';

        $idx = 0;
        foreach ($coins as $strAmount => $quantity) {
            $idx++;
            $message .= $quantity . 'x' . $this->currencyFormatter->formatCurrency($strAmount);

            if ($idx != count(array_keys($coins))) {
                $message .= ', ';
            }
        }

        return $message;
    }

    public function getSuccessullyPasteCoinMsg(float $coinPrice, float $allAvailalbeMoney) {
        return 'Успешно поставихте ' . $this->currencyFormatter->formatCurrency($coinPrice) . ', текущата Ви сума е ' . $this->currencyFormatter->formatCurrency($allAvailalbeMoney);
    }

    public function setCurrencyFormatter(CurrencyFormatter $currencyFormatter) {
        $this->currencyFormatter = $currencyFormatter;

        $this->formatAllPossibleCoins();
    }

    public function setInventory(Inventory $inventory) {
        $this->inventory = $inventory;
    }

    public function getAllPossibleCoins(): array {
        return $this->configValidations['coins'];
    }

    public function validateCoin($coin, array $possibleCoins = []) {
        return $this->validateMoneyAmount($coin) && in_array($coin, empty($possibleCoins) ? $this->getAllPossibleCoins() : $possibleCoins);
    }
    
    protected function validateInventory() {
        foreach ($this->inventory->getProductCatalog() as $product => $price) {
            if (
                !is_string($product) ||
                empty($product) ||
                !$this->validateMoneyAmount($price)
            ) {
                // Product should be not empty string
                // Coins should be floats with 2 decimal digits: xx.xx, higher than zero
                return false;
            }
        }

        return true;
    }

    protected function validateConfigCoins(): bool {
        foreach ($this->getAllPossibleCoins() as $coin) {
            if (!$this->validateMoneyAmount($coin)) {
                // Coins should be floats with 2 decimal digits: xx.xx, higher than zero
                return false;
            }
        }

        return true;
    }

    /**
     * Validates $coin to [0, +infinity)
     */
    public function validateMoneyAmount($coin): bool {
        return (is_float($coin) || is_int($coin)) && $coin >= 0 && round($coin, 2) === (float) $coin;
    }

    /**
     * Call when the CurrencyFormatter is already set
     */
    private function formatAllPossibleCoins() {
        foreach ($this->getAllPossibleCoins() as $coin) {
            $this->formatedCoinsStrs[] = $this->currencyFormatter->formatCurrency($coin);
        }
    }
}
