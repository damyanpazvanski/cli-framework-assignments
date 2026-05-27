<?php

namespace Apps\AdvertisingBidAuction\Core;

use Common\Apps\CoreCLIApp;
use Common\Commands\ArgsHandler;
use Apps\AdvertisingBidAuction\Core\Validators\CSVValidator;
use Common\Interfaces\IRepository;
use Apps\AdvertisingBidAuction\Core\Repositories\CSVFileRepository;
use Apps\AdvertisingBidAuction\Core\Files\CSVFile;

class App extends CoreCLIApp
{
	public function __construct() {
		parent::__construct(
			__DIR__ . './config/app.php',
			__DIR__ . './config/handlers.php',
			__DIR__ . './config/commands.php',
			__DIR__ . './config/validations.php'
		);
	}

	public function run() {
		$CSVFileRepository = $this->resolve(CSVFileRepository::class, [new CSVFile], null, IRepository::class);

		$this->resolveCommand(ArgsHandler::getAction(), ...[
			$CSVFileRepository,
			ArgsHandler::getArgs(),
			ArgsHandler::getFlags()
		]);
    }
}
