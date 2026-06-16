<?php

namespace Apps\VendingMachine\Core\Helpers;

class CurrencyFormatter
{
    public const CURRENCY_POSITION_BEFORE = 0;  // Place the currency before the digit
    public const CURRENCY_POSITION_AFTER = 1;   // Place the currency after the digit

    protected string $currencySign;
    protected string $space;
    protected int $signPosition;

    public function __construct(array $config) {
        $this->currencySign = $config['sign'];
        $this->space = $config['space'];
        $this->signPosition = $config['position'];
    }

    public function formatStr(string $str): string {
        if ($this->signPosition == self::CURRENCY_POSITION_BEFORE) {
            return $this->currencySign . $this->space . $str;
        }

        return $str . $this->space . $this->currencySign;
    }

    public function formatCurrency(float $price): string {
        $price = number_format($price, 2, '.', '');

        return $this->formatStr($price);
    }
}
