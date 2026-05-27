<?php

namespace Common\ErrorHandlers;

use Common\Interfaces\IGlobalHandler;

class CLIErrorHandler implements IGlobalHandler
{
    public static function register(array $args = []): void {
        if ($args['production']) {
            set_exception_handler([self::class, 'handleException']);

            set_error_handler([self::class, 'handleError']);
    
            register_shutdown_function([self::class, 'handleShutdown']);    
        }
    }

    public static function handleException(\Throwable $e): void {
        self::log("ERROR", $e->getMessage(), 31); // Red
        exit(1);
    }

    public static function handleError($level, $message, $file, $line): void {
        // Respect error_reporting settings
        if (!(error_reporting() & $level)) return;
        
        self::log("WARNING", "$message in $file on line $line", 33); // Yellow
    }

    public static function handleShutdown(): void {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::log("FATAL", $error['message'], 31);
        }
    }

    private static function log(string $label, string $message, int $colorCode): void {
        $color = "\033[{$colorCode}m";
        $reset = "\033[0m";
        fwrite(STDERR, "{$color}[{$label}]{$reset} {$message}" . PHP_EOL);
    }
}

