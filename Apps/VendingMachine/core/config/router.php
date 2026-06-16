<?php

use Apps\VendingMachine\Core\Controllers\HomeController;
use Apps\VendingMachine\Core\Controllers\VendingMachineController;

use Apps\VendingMachine\Core\Controllers\API\APIVendingMachineController;
use Apps\VendingMachine\Core\Controllers\API\APIProductController;
use Apps\VendingMachine\Core\Controllers\API\APICoinController;

use Apps\VendingMachine\Core\Loggers\DisplayLogger;

use Apps\VendingMachine\Core\Services\VendingMachineHardService;

use Apps\VendingMachine\Core\Requests\Request;

use Apps\VendingMachine\Core\Helpers\CurrencyFormatter;
use Apps\VendingMachine\Core\Helpers\Inventory;
use Apps\VendingMachine\Core\Helpers\Wallet;

use Apps\VendingMachine\Core\Repositories\ProductRepository;
use Apps\VendingMachine\Core\Repositories\CoinRepository;

use Apps\VendingMachine\Core\Structures\RollingBacklog;

return [
    '/home' => [
        'options' => [
            'name' => 'home',
            'action' => 'home',
            'method' => 'GET'
        ],
        'controller' => [
            HomeController::class => [
                Request::class,
            ]
        ]
    ],
    '/vending-machine' => [
        'options' => [
            'name' => 'showVendingMachine',
            'action' => 'show',
            'method' => 'GET'
        ],
        'controller' => [
            VendingMachineController::class => [
                Request::class,
            ]
        ]
    ],

    // ======================= API Routes =======================
    '/api/vending-machine' => [
        'options' => [
            'action' => 'initial',
            'method' => 'GET'
        ],
        'controller' => [
            APIVendingMachineController::class => [
                Request::class,
                VendingMachineHardService::class => [
                    ProductRepository::class,
                    CoinRepository::class,
                    Wallet::class => [
                        'Balance' => 0.00,
                    ],
                    DisplayLogger::class => [
                        RollingBacklog::class => [
                            'keep' => 10,
                        ],
                        CurrencyFormatter::class => [
                            'sign' => '$',
                            'space' => '',
                            'position' => CurrencyFormatter::CURRENCY_POSITION_BEFORE,
                        ],
                    ],
                ],
            ]
        ]
    ],
    '/api/vending-machine/put-coin' => [
        'options' => [
            'action' => 'putCoin',
            'method' => 'POST'
        ],
        'controller' => [
            APIVendingMachineController::class => [
                Request::class,
                VendingMachineHardService::class => [
                    ProductRepository::class,
                    CoinRepository::class,
                    Wallet::class => [
                        'Balance' => 0.00,
                    ],
                    DisplayLogger::class => [
                        RollingBacklog::class => [
                            'keep' => 10,
                        ],
                        CurrencyFormatter::class => [
                            'sign' => '$',
                            'space' => '',
                            'position' => CurrencyFormatter::CURRENCY_POSITION_BEFORE,
                        ],
                    ],
                ],
            ],
        ]
    ],
    '/api/vending-machine/get-change' => [
        'options' => [
            'action' => 'getChange',
            'method' => 'POST'
        ],
        'controller' => [
            APIVendingMachineController::class => [
                Request::class,
                VendingMachineHardService::class => [
                    ProductRepository::class,
                    CoinRepository::class,
                    Wallet::class => [
                        'Balance' => 0.00,
                    ],
                    DisplayLogger::class => [
                        RollingBacklog::class => [
                            'keep' => 10,
                        ],
                        CurrencyFormatter::class => [
                            'sign' => '$',
                            'space' => '',
                            'position' => CurrencyFormatter::CURRENCY_POSITION_BEFORE,
                        ],
                    ],
                ],
            ],
        ]
    ],
    '/api/vending-machine/buy-product' => [
        'options' => [
            'action' => 'buyProduct',
            'method' => 'POST'
        ],
        'controller' => [
            APIVendingMachineController::class => [
                Request::class,
                VendingMachineHardService::class => [
                    ProductRepository::class,
                    CoinRepository::class,
                    Wallet::class => [
                        'Balance' => 0.00,
                    ],
                    DisplayLogger::class => [
                        RollingBacklog::class => [
                            'keep' => 10,
                        ],
                        CurrencyFormatter::class => [
                            'sign' => '$',
                            'space' => '',
                            'position' => CurrencyFormatter::CURRENCY_POSITION_BEFORE,
                        ],
                    ],
                ],
            ],
        ]
    ],
    '/api/vending-machine/view-amount' => [
        'options' => [
            'action' => 'viewAmount',
            'method' => 'POST'
        ],
        'controller' => [
            APIVendingMachineController::class => [
                Request::class,
                VendingMachineHardService::class => [
                    ProductRepository::class,
                    CoinRepository::class,
                    Wallet::class => [
                        'Balance' => 0.00,
                    ],
                    DisplayLogger::class => [
                        RollingBacklog::class => [
                            'keep' => 10,
                        ],
                        CurrencyFormatter::class => [
                            'sign' => '$',
                            'space' => '',
                            'position' => CurrencyFormatter::CURRENCY_POSITION_BEFORE,
                        ],
                    ],
                ],
            ],
        ]
    ],

    '/api/vending-machine/products' => [
        'options' => [
            'action' => 'save',
            'method' => 'POST'
        ],
        'controller' => [
            APIProductController::class => [
                Request::class,
                VendingMachineHardService::class => [
                    ProductRepository::class,
                    CoinRepository::class,
                    Wallet::class => [
                        'Balance' => 0.00,
                    ],
                    DisplayLogger::class => [
                        RollingBacklog::class => [
                            'keep' => 10,
                        ],
                        CurrencyFormatter::class => [
                            'sign' => '$',
                            'space' => '',
                            'position' => CurrencyFormatter::CURRENCY_POSITION_BEFORE,
                        ],
                    ],
                ],
            ]
        ]
    ],
    '/api/vending-machine/products/delete' => [
        'options' => [
            'action' => 'delete',
            'method' => 'POST'
        ],
        'controller' => [
            APIProductController::class => [
                Request::class,
                VendingMachineHardService::class => [
                    ProductRepository::class,
                    CoinRepository::class,
                    Wallet::class => [
                        'Balance' => 0.00,
                    ],
                    DisplayLogger::class => [
                        RollingBacklog::class => [
                            'keep' => 10,
                        ],
                        CurrencyFormatter::class => [
                            'sign' => '$',
                            'space' => '',
                            'position' => CurrencyFormatter::CURRENCY_POSITION_BEFORE,
                        ],
                    ],
                ],
            ]
        ]
    ],
    '/api/vending-machine/coins' => [
        'options' => [
            'action' => 'save',
            'method' => 'POST'
        ],
        'controller' => [
            APICoinController::class => [
                Request::class,
                VendingMachineHardService::class => [
                    ProductRepository::class,
                    CoinRepository::class,
                    Wallet::class => [
                        'Balance' => 0.00,
                    ],
                    DisplayLogger::class => [
                        RollingBacklog::class => [
                            'keep' => 10,
                        ],
                        CurrencyFormatter::class => [
                            'sign' => '$',
                            'space' => '',
                            'position' => CurrencyFormatter::CURRENCY_POSITION_BEFORE,
                        ],
                    ],
                ],
            ],
        ]
    ],
    '/api/vending-machine/coins/delete' => [
        'options' => [
            'action' => 'delete',
            'method' => 'POST'
        ],
        'controller' => [
            APICoinController::class => [
                Request::class,
                VendingMachineHardService::class => [
                    ProductRepository::class,
                    CoinRepository::class,
                    Wallet::class => [
                        'Balance' => 0.00,
                    ],
                    DisplayLogger::class => [
                        RollingBacklog::class => [
                            'keep' => 10,
                        ],
                        CurrencyFormatter::class => [
                            'sign' => '$',
                            'space' => '',
                            'position' => CurrencyFormatter::CURRENCY_POSITION_BEFORE,
                        ],
                    ],
                ],
            ],
        ]
    ],

    'otherwise' => [
        'options' => [
            'action' => 'notFound404',
            'method' => 'GET'
        ],
        'controller' => [
            HomeController::class => [
                Request::class,
            ]
        ]
    ]
];
