<?php

namespace CommonF\Loggers;

use CommonF\Interfaces\ILoggerAdapter;

abstract class HTTPLoggerAbstract implements ILoggerAdapter
{
    public function log(string $msg, string $label = 'INFO', int $colorCode = 0) {
        echo "[{$label}]: {$msg}" . PHP_EOL;
    }

    public function success(string $msg, string $label = 'SUCCESS') {
        $this->log($msg, $label);
    }

    public function warning(string $msg, string $label = 'WARNING') {
        $this->log($msg, $label);
    }

    public function error(string $msg, string $label = 'ERROR') {
        $this->log($msg, $label);
    }
}
