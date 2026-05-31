<?php

namespace Apps\EscapeALabyrinth\Core;

use CommonF\Apps\CoreCLIApp;
use CommonF\Commands\ArgsHandler;

class App extends CoreCLIApp
{
	public function __construct() {
		parent::__construct(
			__DIR__ . './config/commands.php',
			__DIR__ . './config/app.php',
			__DIR__ . './config/handlers.php',
			__DIR__ . './config/validations.php'
		);
	}

	public function run() {
		$this->resolveCommand(ArgsHandler::getAction(), ...[ArgsHandler::getArgs(), ArgsHandler::getFlags()]);
    }
}
