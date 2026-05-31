<?php

namespace CommonF\Interfaces;

use CommonF\Interfaces\ILoggerAdapter;

interface IGlobalHandler
{
    public static function register(array $args = []): void;
    public static function registerLogger(ILoggerAdapter $loggerAdapter): void;
}
