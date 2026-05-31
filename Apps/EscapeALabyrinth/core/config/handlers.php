<?php

use CommonF\ErrorHandlers\CLIErrorHandler;
use CommonF\Commands\ArgsHandler;

use Apps\EscapeALabyrinth\Core\Loggers\SimpleLogger;

return [
    CLIErrorHandler::class => [
        'logger' => SimpleLogger::class
    ],
    ArgsHandler::class => ['countLimit' => 20],     // Limitate to 20 args
];
