<?php

namespace Apps\VendingMachine\Core\Commands;

use CommonF\Commands\CommandAbstract;
use Apps\VendingMachine\Core\Loggers\SimpleCLILogger;

class RemoveSQLite extends CommandAbstract
{
    protected SimpleCLILogger $simpleLogger;
    protected array $dbConfig;

    public function __construct(SimpleCLILogger $simpleLogger, $dbConfig, $options, $flags) {
        $this->simpleLogger = $simpleLogger;
        $this->dbConfig = $dbConfig;
    }

    public function execute(): void {
        $fullDbPath = $this->dbConfig['host'] . DIRECTORY_SEPARATOR . $this->dbConfig['file'];

        clearstatcache();       // Clear PHP's internal file cache
        gc_collect_cycles();    // Force PHP garbage collection to drop dead locks
        
        usleep(1000000);        // Wait for a second

        if (file_exists($fullDbPath)) {
            if (unlink($fullDbPath)) {
                $this->simpleLogger->log('The Database File was successfully deleted');
            } else {
                $this->simpleLogger->warning('Could not remove the Database File');
            }
        } else {
            $this->simpleLogger->log('The Database File is not existing');
        }

        if (is_dir($this->dbConfig['host'])) {
            if (rmdir($this->dbConfig['host'])) {
                $this->simpleLogger->log('Empty directory removed');
            } else {
                $this->simpleLogger->warning('Could not remove the directory');
            }
        }
    }
}
