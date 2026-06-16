<?php

use Apps\VendingMachine\Core\Commands\MigrateSQLite;
use Apps\VendingMachine\Core\Commands\RemoveSQLite;
use Apps\VendingMachine\Core\Commands\VendingMachineEasy;
use Apps\VendingMachine\Core\Commands\VendingMachineMedium;

use Apps\VendingMachine\Core\Loggers\SimpleCLILogger;
use Apps\VendingMachine\Core\Loggers\CLIVendingLogger;
use Apps\VendingMachine\Core\Loggers\DisplayLogger;

use Apps\VendingMachine\Core\Services\VendingMachineEasyService;
use Apps\VendingMachine\Core\Services\VendingMachineMediumService;

use Apps\VendingMachine\Core\Helpers\CurrencyFormatter;
use Apps\VendingMachine\Core\Helpers\Inventory;
use Apps\VendingMachine\Core\Helpers\Wallet;

use Apps\VendingMachine\Core\Structures\RollingBacklog;

return [
    'migrate' => [
        MigrateSQLite::class => [
            SimpleCLILogger::class,
        ]
    ],
    'remove' => [
        RemoveSQLite::class => [
            SimpleCLILogger::class,
        ]
    ],
    'vending-easy' => [
        VendingMachineEasy::class => [
            VendingMachineEasyService::class => [
                CurrencyFormatter::class => [
                    'sign' => 'лв.',
                    'space' => '',
                    'position' => CurrencyFormatter::CURRENCY_POSITION_AFTER,
                ],
                Inventory::class => [
                    'Milk' => 0.50,
                    'Espresso' => 0.40,
                    'Long Espresso' => 0.60,
                ],
                CLIVendingLogger::class,
            ],
        ]
    ],
    'vending-medium' => [
        VendingMachineMedium::class => [
            VendingMachineMediumService::class => [
                Inventory::class => [
                    'Milk' => 0.50,
                    'Espresso' => 0.40,
                    'Long Espresso' => 0.60,
                ],
                Wallet::class => [
                    'Balance' => 0.00,                  // Initial User Balance
                ],
                DisplayLogger::class => [
                    RollingBacklog::class => [
                        'keep' => 10,                   // Keep latest n logs in the memory
                    ],
                    CurrencyFormatter::class => [
                        'sign' => '$',
                        'space' => '',
                        'position' => CurrencyFormatter::CURRENCY_POSITION_BEFORE,
                    ],
                ]
            ],
        ]
    ]
];
