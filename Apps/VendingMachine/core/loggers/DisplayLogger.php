<?php

namespace Apps\VendingMachine\Core\Loggers;

use Apps\VendingMachine\Core\Loggers\CLIVendingLogger;
use Apps\VendingMachine\Core\Structures\RollingBacklog;
use Apps\VendingMachine\Core\Helpers\CurrencyFormatter;

class DisplayLogger extends CLIVendingLogger
{
    protected RollingBacklog $rollingBacklog;
    protected CurrencyFormatter $currencyFormatter;
    protected array $formatedCoinsStrs = [];

    public function __construct(RollingBacklog $rollingBacklog, CurrencyFormatter $currencyFormatter) {
        $this->rollingBacklog = $rollingBacklog;
        $this->currencyFormatter = $currencyFormatter;
    }

    public function log(string $msg, string $label = 'INFO', int $colorCode = 33) {
        $this->rollingBacklog->add("[{$label}] {$msg}");
    }

    public function all() {
        $logs = $this->rollingBacklog->getHistory();

        foreach ($logs as $log) {
            echo $log . PHP_EOL;
        }
    }

    public function getDuplicatedMsg() {
        return 'Подобен запис вече съществува.';
    }

    public function getInsertedProductMsg() {
        return 'Успешно създадохте нова напитка.';
    }

    public function getDeletedProductMsg() {
        return 'Успешно изтрихте напитка.';
    }

    public function getInsertedCoinMsg() {
        return 'Успешно създадохте монета.';
    }

    public function getDeletedCoinMsg() {
        return 'Успешно изтрихте монета.';
    }

    public function getWrongProductMsg() {
        return 'Исканият продукт нe е намерен.';
    }

    public function getWrongCoinMsg() {
        return 'Автомата приема монети от: ' . implode(', ', $this->formatedCoinsStrs);
    }

    public function getWrongPriceMsg() {
        return 'Сумата трябва да има положителна стойност.';
    }

    public function getWrongStrMsg(string $field) {
        return "Полето: {$field} трябва да има стойност.";
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

    /**
     * $productCatalog <string, float>[]
     */
    public function getViewDrinksMsg(array $productCatalog): string {
        $message = 'Напитки:' . PHP_EOL;

        foreach ($productCatalog as $product => $price) {
            $message .= $product . ': ' . $this->currencyFormatter->formatCurrency($price) . PHP_EOL;
        }

        return $message;
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

    public function getCurrencyFormatter(): CurrencyFormatter {
        return $this->currencyFormatter;
    }

    public function getSuccessullyPasteCoinMsg(float $coinPrice, float $allAvailalbeMoney) {
        return 'Успешно поставихте ' . $this->currencyFormatter->formatCurrency($coinPrice) . ', текущата Ви сума е ' . $this->currencyFormatter->formatCurrency($allAvailalbeMoney);
    }

    public function keepFormatedAllPossibleCoins(array $coins) {
        foreach ($coins as $coin) {
            $this->formatedCoinsStrs[] = $this->currencyFormatter->formatCurrency($coin);
        }
    }
}
