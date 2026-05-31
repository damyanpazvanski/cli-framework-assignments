<?php

namespace CommonF\ErrorHandlers;

use CommonF\Interfaces\IGlobalHandler;
use CommonF\Interfaces\ILoggerAdapter;

class CLIErrorHandler implements IGlobalHandler
{
    public static ILoggerAdapter $logger;

    public static function register(array $args = []): void {
        if ($args['production']) {
            set_exception_handler([self::class, 'handleException']);

            set_error_handler([self::class, 'handleError']);
    
            register_shutdown_function([self::class, 'handleShutdown']);    
        }
    }

    public static function registerLogger(ILoggerAdapter $loggerAdapter): void {
        self::$logger = $loggerAdapter;
    }

    public static function handleException(\Throwable $e): void {
        self::$logger->error($e->getMessage()); exit(1);
    }

    public static function handleError($level, $message, $file, $line): void {
        // Respect error_reporting settings
        if (!(error_reporting() & $level)) return;

        self::$logger->warning("$message in $file on line $line");
    }

    public static function handleShutdown(): void {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::$logger->error($error['message'], 'FATAL');
        }
    }
}

