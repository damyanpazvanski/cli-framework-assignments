<?php

use CommonF\ErrorHandlers\HTTPErrorHandler;
use CommonF\Commands\ArgsHandler;

use Apps\VendingMachine\Core\Loggers\SimpleHTTPLogger;

return [
    HTTPErrorHandler::class => [
        'logger' => SimpleHTTPLogger::class
    ],
    ArgsHandler::class => ['countLimit' => 10],     // Limitate to 10 args
];
