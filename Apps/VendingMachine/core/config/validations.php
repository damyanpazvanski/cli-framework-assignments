<?php

use Apps\VendingMachine\Core\Commands\VendingMachineEasy;
use Apps\VendingMachine\Core\Commands\VendingMachineMedium;

use Apps\VendingMachine\Core\Validators\VendingMachineEasyValidator;
use Apps\VendingMachine\Core\Validators\VendingMachineMediumValidator;

use Apps\VendingMachine\Core\Controllers\API\APIVendingMachineController;
use Apps\VendingMachine\Core\Controllers\API\APIProductController;
use Apps\VendingMachine\Core\Controllers\API\APICoinController;

return [
    VendingMachineEasy::class => [
        VendingMachineEasyValidator::class => [
            'coins' => [0.05, 0.10, 0.20, 0.50, 1.00],      // Possible coins
        ]
    ],
    VendingMachineMedium::class => [
        VendingMachineMediumValidator::class => [
            'coins' => [0.05, 0.10, 0.20, 0.50, 1.00],      // Possible coins
        ]
    ],

    APIVendingMachineController::class => [
        VendingMachineMediumValidator::class
    ],
    APIProductController::class => [
        VendingMachineMediumValidator::class
    ],
    APICoinController::class => [
        VendingMachineMediumValidator::class
    ],
];
