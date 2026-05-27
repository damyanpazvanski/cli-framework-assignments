<?php

use Common\ErrorHandlers\CLIErrorHandler;
use Common\Commands\ArgsHandler;

return [
    CLIErrorHandler::class,
    ArgsHandler::class => ['countLimit' => 10],     // Limitate to 10 args
];
