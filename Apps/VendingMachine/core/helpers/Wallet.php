<?php

namespace Apps\VendingMachine\Core\Helpers;

class Wallet
{
    protected float $availableMoney = 0.0;

    public function __construct(array $config) {
        $this->availableMoney = $config['Balance'] ?? $this->availableMoney;
    }

    public function set(float $amount) {
        return $this->availableMoney = $amount;
    }

    public function add(float $amount) {
        return $this->availableMoney += $amount;
    }

    public function substract(float $amount) {
        return $this->availableMoney -= $amount;
    }

    public function getAmount(): float {
        return $this->availableMoney;
    }

    /**
     * return <string, <int>quantity>[]
     */
    public function getLeftAmountAsCoins(array $allPossibleCoins): array {
        rsort($allPossibleCoins, SORT_NUMERIC);

        /**
         * $coins<string, int>
         */
        $coins = [];
        $fullAmount = $this->getAmount();
        foreach ($allPossibleCoins as $coin) {
            // Casting is required because floor is not enough sensitive to decimals
            $cCount = floor((string) ($fullAmount / $coin));

            if ($cCount > 0) {
                $fullAmount -= $cCount * $coin;
                $coins[(string) $coin] = $cCount;

                if ($fullAmount == 0) {
                    break;
                }
            }
        }

        return $coins;
    }
}
