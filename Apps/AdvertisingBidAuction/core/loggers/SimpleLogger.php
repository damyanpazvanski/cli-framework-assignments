<?php

namespace Apps\AdvertisingBidAuction\Core\Loggers;

use CommonF\Loggers\CLILoggerAbstract;

class SimpleLogger extends CLILoggerAbstract
{
    public function plain(string $msg, int $colorCode = 33) {
        echo "{$color} {$msg}" . PHP_EOL;
    }
}
