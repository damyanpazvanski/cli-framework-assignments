<?php

use CommonF\ErrorHandlers\HTTPErrorHandler;
use CommonF\Commands\ArgsHandler;

use Apps\WordFrequencyCounter\Core\Loggers\SimpleLogger;

return [
    HTTPErrorHandler::class => [
        'logger' => SimpleLogger::class
    ],
];
