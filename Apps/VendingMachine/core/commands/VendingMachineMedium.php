<?php

namespace Apps\VendingMachine\Core\Commands;

use CommonF\Files\FileStream;
use CommonF\Commands\CommandAbstract;
use Apps\VendingMachine\Core\Validators\VendingMachineMediumValidator;
use Apps\VendingMachine\Core\Services\VendingMachineMediumService;

class VendingMachineMedium extends CommandAbstract
{
    protected VendingMachineMediumService $vendingMachineMediumService;

    public function __construct(VendingMachineMediumService $vendingMachineMediumService, $dbConfig, $options, $flags) {
        $this->vendingMachineMediumService = $vendingMachineMediumService;
    }

    public function execute(): void {
        $this->vendingMachineMediumValidator = $this->getValidator(VendingMachineMediumValidator::class);
        $this->vendingMachineMediumService->setValidator($this->vendingMachineMediumValidator);

        if (!$this->vendingMachineMediumValidator->validate()) {
            throw new \Exception('Wrong Configuration');
        }

        $this->vendingMachineMediumService
            ->buyDrink( 'espresso' )
            ->buyDrink( 'Espresso' )
            ->viewDrinks()
            ->putCoin( 2 )
            ->putCoin( 1 )
            ->buyDrink( 'Espresso' )
            ->getCoins()
            ->viewAmount()
            ->getCoins()

            // Get the Display and show the backlog
            ->display()
            ->all()
        ;
    }
}
