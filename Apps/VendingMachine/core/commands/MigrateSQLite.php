<?php

namespace Apps\VendingMachine\Core\Commands;

use CommonF\Commands\CommandAbstract;
use Apps\VendingMachine\Core\Loggers\SimpleCLILogger;

class MigrateSQLite extends CommandAbstract
{
    protected SimpleCLILogger $simpleLogger;
    protected \SQLite3 $db;
    protected array $dbConfig;

    public function __construct(SimpleCLILogger $simpleLogger, $dbConfig, $options, $flags) {
        $this->simpleLogger = $simpleLogger;
        $this->dbConfig = $dbConfig;
    }

    public function execute(): void {
        if (!is_dir($this->dbConfig['host'])) {
            if (!mkdir($this->dbConfig['host'], 0755, true)) {
                $this->simpleLogger->error("Failed to create the folder"); exit(0);
            }
        }

        $fullDbPath = $this->dbConfig['host'] . DIRECTORY_SEPARATOR . $this->dbConfig['file'];

        $this->db = new \SQLite3($fullDbPath);
        $this->db->enableExceptions(true);

        if (!is_writable($fullDbPath)) {
            $this->simpleLogger->error("Datbase file exists but PHP cannot write inside it"); exit(0);
        }

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE,
                price INTEGER NOT NULL DEFAULT 0
            );
            CREATE TABLE IF NOT EXISTS possible_coins (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                price INTEGER NOT NULL UNIQUE
            )
        ");

        $tableNamesCheck = $this->db->querySingle("SELECT COUNT(*) FROM sqlite_master
            WHERE type='table' AND name in ('products', 'possible_coins')");

        if (!$tableNamesCheck == 2) {
            $this->db->close();
            $this->simpleLogger->error("Database initialization error: Table structure could not be built on disk."); exit(0);
        }

        $this->db->exec("
            INSERT INTO `products` (`name`, `price`) VALUES ('Milk', 50);
            INSERT INTO `products` (`name`, `price`) VALUES ('Espresso', 40);
            INSERT INTO `products` (`name`, `price`) VALUES ('Long Espresso', 60);
        ");

        $this->db->exec("
            INSERT INTO `possible_coins` (`price`) VALUES (5);
            INSERT INTO `possible_coins` (`price`) VALUES (10);
            INSERT INTO `possible_coins` (`price`) VALUES (20);
            INSERT INTO `possible_coins` (`price`) VALUES (50);
            INSERT INTO `possible_coins` (`price`) VALUES (100);
        ");

        $this->db->close();
        $this->simpleLogger->log('Migrations were successfully done');
    }
}
