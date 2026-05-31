<?php

namespace CommonF\Commands;

use CommonF\Interfaces\IGlobalHandler;
use CommonF\Interfaces\ILoggerAdapter;

class ArgsHandler implements IGlobalHandler
{
    public static ILoggerAdapter $logger;
    protected static $allValues;
    protected static $allValuesCount;

    protected static $countLimit;

    protected static $options = [];
    protected static $flags = [];

    public static function register(array $args = []): void {
        self::$countLimit = isset($args['countLimit']) ? $args['countLimit'] : 0;
        self::$allValues = array_slice($_SERVER['argv'], 1);
        self::$allValuesCount = $_SERVER['argc'];

        self::proccessArgs();
    }

    public static function registerLogger(ILoggerAdapter $loggerAdapter): void {
        self::$logger = $loggerAdapter;
    }

    public static function proccessArgs(): void {
        if (self::$countLimit > 0 && self::$allValuesCount > self::$countLimit) {
            throw new \Exception('Too much command arguments!');
        }

        foreach(self::$allValues as $val) {
            if (substr($val, 0, 2) === '--') {
                self::$flags[] = $val;
            } else {
                self::$options[] = $val;
            }
        }
    }

    public static function getAction(): string {
        return empty(self::$options) ? '' : self::$options[0];
    }

    public static function getArgs(): array {
        return array_slice(self::$options, 1);
    }

    public static function getFlags(): array {
        return self::$flags;
    }
}

