<?php

namespace Apps\AdvertisingBidAuction\Tests\Commands;

use PHPUnit\Framework\TestCase;

use Apps\AdvertisingBidAuction\Core\Loggers\SimpleLogger;
use CommonF\Apps\CoreCLIApp;
use CommonF\Commands\ArgsHandler;

class CLIAppTest extends TestCase {
    protected const APP_PREFIX = __DIR__ . '/../../';
    protected static CoreCLIApp $CLIApp;
    protected static SimpleLogger $simpleLogger;
    private int $initialObLevel;

    protected function reloadGlobalArgsHandler() {
        ArgsHandler::register(require self::APP_PREFIX . 'core/config/handlers.php');
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        
        self::$simpleLogger = new SimpleLogger();
        self::$CLIApp = new CoreCLIApp(
            self::APP_PREFIX . 'core/config/commands.php',
            self::APP_PREFIX . 'core/config/app.php',
            self::APP_PREFIX . 'core/config/handlers.php',
            self::APP_PREFIX . 'core/config/validations.php'
        );
    }

    // Runs before every test
    protected function setUp(): void { parent::setUp(); }

    /**
     * @covers CoreCLIApp::resolveCommand
     */
    public function testCSVFileProcessNoFlags() {
        $_SERVER['argv'] = ['index.php', 'best-ad-second-bid', __DIR__ . '/../File.csv'];
        $_SERVER['argc'] = 3;

        $this->reloadGlobalArgsHandler();

        ob_start();
        self::$simpleLogger->success('4, 33');
        $output = ob_get_clean();

        $this->expectOutputString($output);
        self::$CLIApp->resolveCommand(ArgsHandler::getAction(), ...[ArgsHandler::getArgs(), ArgsHandler::getFlags()]);
    }

    /**
     * @covers CoreCLIApp::resolveCommand
     */
    public function testCSVFileErrProcessNoFlags() {
        $_SERVER['argv'] = ['index.php', 'best-ad-second-bid', __DIR__ . '/../FileErr.csv'];
        $_SERVER['argc'] = 3;

        $this->reloadGlobalArgsHandler();

        $this->expectExceptionMessage('Row - 5 has wrong data: ["32"," a","   d12"]');
        self::$CLIApp->resolveCommand(ArgsHandler::getAction(), ...[ArgsHandler::getArgs(), ArgsHandler::getFlags()]);
    }

    /**
     * @covers CoreCLIApp::resolveCommand
     */
    public function testCSVFileProcessAllFlags() {
        $_SERVER['argv'] = ['index.php', 'best-ad-second-bid', __DIR__ . '/../File.csv', '--continue', '--print-warnings'];
        $_SERVER['argc'] = 5;

        $this->reloadGlobalArgsHandler();

        ob_start();
        self::$simpleLogger->success('4, 33');
        $output = ob_get_clean();

        $this->expectOutputString($output);
        self::$CLIApp->resolveCommand(ArgsHandler::getAction(), ...[ArgsHandler::getArgs(), ArgsHandler::getFlags()]);
    }

    /**
     * @covers CoreCLIApp::resolveCommand
     */
    public function testCSVFileErrProcessAllFlags() {
        $_SERVER['argv'] = ['index.php', 'best-ad-second-bid', __DIR__ . '/../FileErr.csv', '--continue', '--print-warnings'];
        $_SERVER['argc'] = 5;

        $this->reloadGlobalArgsHandler();

        $output = [
            'Row - 5 has wrong data: ["32"," a","   d12"]',
            'Row - 6 has wrong data: ["4"," a","   3a3.5"]',
            'Row - 8 has wrong data: [null]',
            'Row - 9 has wrong data: [null]',
        ];

        ob_start();
        self::$CLIApp->resolveCommand(ArgsHandler::getAction(), ...[ArgsHandler::getArgs(), ArgsHandler::getFlags()]);

        $commOutput = ob_get_clean();
        $actualLines = array_map('trim', explode("\n", str_replace("\r\n", "\n", $commOutput)));

        $this->assertEquals($output, array_slice($actualLines, 0, 4));
    }

    /**
     * @covers CoreCLIApp::resolveCommand
     */
    public function testCSVFileProcessContinueFlag() {
        $_SERVER['argv'] = ['index.php', 'best-ad-second-bid', __DIR__ . '/../File.csv', '--continue'];
        $_SERVER['argc'] = 4;

        $this->reloadGlobalArgsHandler();

        ob_start();
        self::$simpleLogger->success('4, 33');
        $output = ob_get_clean();

        $this->expectOutputString($output);
        self::$CLIApp->resolveCommand(ArgsHandler::getAction(), ...[ArgsHandler::getArgs(), ArgsHandler::getFlags()]);
    }

    /**
     * @covers CoreCLIApp::resolveCommand
     */
    public function testCSVFileErrProcessContinueFlag() {
        $_SERVER['argv'] = ['index.php', 'best-ad-second-bid', __DIR__ . '/../FileErr.csv', '--continue'];
        $_SERVER['argc'] = 4;

        $this->reloadGlobalArgsHandler();

        ob_start();
        self::$simpleLogger->success('2, 12.3455');
        $output = ob_get_clean();

        $this->expectOutputString($output);
        self::$CLIApp->resolveCommand(ArgsHandler::getAction(), ...[ArgsHandler::getArgs(), ArgsHandler::getFlags()]);
    }
}
