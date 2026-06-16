<?php

namespace Apps\VendingMachine\Core\Validators;

use Apps\VendingMachine\Core\Validators\VendingMachineEasyValidator;
use Apps\VendingMachine\Core\Helpers\Inventory;
use Apps\VendingMachine\Core\Helpers\Wallet;

class VendingMachineMediumValidator extends VendingMachineEasyValidator
{
    protected Wallet $wallet;

    public function validate(): bool {
        // Validate Initial State
        return parent::validate() && $this->validateWallet();
    }

    public function setWallet(Wallet $wallet) {
        $this->wallet = $wallet;
    }

    protected function validateWallet(): bool {
        return $this->validateMoneyAmount($this->wallet->getAmount());
    }
}
