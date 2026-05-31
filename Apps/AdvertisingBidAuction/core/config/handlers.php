<?php

use CommonF\ErrorHandlers\CLIErrorHandler;
use CommonF\Commands\ArgsHandler;

use Apps\AdvertisingBidAuction\Core\Loggers\SimpleLogger;

return [
    CLIErrorHandler::class => [
        'logger' => SimpleLogger::class
    ],
    ArgsHandler::class => ['countLimit' => 10],     // Limitate to 10 args
];
