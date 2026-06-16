<?php

namespace Apps\VendingMachine\Core\Commands;

use CommonF\Files\FileStream;
use CommonF\Commands\CommandAbstract;
use Apps\VendingMachine\Core\Validators\VendingMachineEasyValidator;
use Apps\VendingMachine\Core\Services\VendingMachineEasyService;

class VendingMachineEasy extends CommandAbstract
{
    protected VendingMachineEasyService $vendingMachineEasyService;

    public function __construct(VendingMachineEasyService $vendingMachineEasyService, $dbConfig, $options, $flags) {
        $this->vendingMachineEasyService = $vendingMachineEasyService;
    }

    public function execute(): void {
        $this->vendingMachineEasyValidator = $this->getValidator(VendingMachineEasyValidator::class);
        $this->vendingMachineEasyService->setValidator($this->vendingMachineEasyValidator);

        if (!$this->vendingMachineEasyValidator->validate()) {
            throw new \Exception('Wrong Configuration');
        }

        $this->vendingMachineEasyService
            ->buyDrink( 'espresso' )
            ->buyDrink( 'Espresso' )
            ->viewDrinks()
            ->putCoin( 2 )
            ->putCoin( 1 )
            ->buyDrink( 'Espresso' )
            ->getCoins()
            ->viewAmount()
            ->getCoins();
    }
}
