<?php

namespace Apps\WordFrequencyCounter\Core;

use CommonF\Apps\CoreHTTPApp;

class App extends CoreHTTPApp
{
	public function __construct() {
		parent::__construct(
			__DIR__ . './config/router.php',
			__DIR__ . './config/app.php',
			__DIR__ . './config/handlers.php',
			__DIR__ . './config/validations.php',
		);
	}

	public function run() {
		$path = $_SERVER['REQUEST_URI'];
		$method = $_SERVER['REQUEST_METHOD'];

		$routeKey = $this->findRouteKey($path, $method);

		$selectedDB = $this->appConfig['selectedDatabase'];

		$this->resolveController($routeKey, ...[$this->appConfig['database'][$selectedDB]]);
    }
}
