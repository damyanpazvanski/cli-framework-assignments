<?php

namespace Apps\VendingMachine\Core;

use CommonF\Apps\CoreHTTPApp;

/**
 * Request maximum allowed time is 30sec
 */
set_time_limit(30);

/**
 * The code is written to handle every request without memory spikes but in any case
 * allocate all possible memory available to the server
 */
// ini_set('memory_limit', '-1');

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
