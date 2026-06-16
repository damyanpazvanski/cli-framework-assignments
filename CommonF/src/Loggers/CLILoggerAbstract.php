<?php

namespace CommonF\Loggers;

use CommonF\Interfaces\ILoggerAdapter;

abstract class CLILoggerAbstract implements ILoggerAdapter
{
    public function log(string $msg, string $label = 'INFO', int $colorCode = 33) {
        $color = "\033[{$colorCode}m";
        $reset = "\033[0m";

        echo "{$color}[{$label}]{$reset} {$msg}" . PHP_EOL;
    }

    public function success(string $msg, string $label = 'SUCCESS') {
        $this->log($msg, $label, 32); // 32 = Green
    }

    public function warning(string $msg, string $label = 'WARNING') {
        $this->log($msg, $label, 33); // 33 = Yellow
    }

    public function error(string $msg, string $label = 'ERROR') {
        $this->log($msg, $label, 31); // 31 = Red
    }
}
