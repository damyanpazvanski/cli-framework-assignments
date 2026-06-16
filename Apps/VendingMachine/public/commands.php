<?php

require_once __DIR__ . './../../../CommonF/autoloader.php';

use CommonF\Commands\ArgsHandler;
use CommonF\Apps\CoreCLIApp;

$app = new CoreCLIApp(
    __DIR__ . '/../core/config/commands.php',
    __DIR__ . '/../core/config/app.php',
    __DIR__ . '/../core/config/CLIHandlers.php',
    __DIR__ . '/../core/config/validations.php'
);

$selectedDB = $app->appConfig['selectedDatabase'];

$app->resolveCommand(ArgsHandler::getAction(), ...[$app->appConfig['database'][$selectedDB], ArgsHandler::getArgs(), ArgsHandler::getFlags()]);
