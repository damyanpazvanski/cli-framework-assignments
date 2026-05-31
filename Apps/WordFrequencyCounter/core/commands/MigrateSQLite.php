<?php

namespace Apps\WordFrequencyCounter\Core\Commands;

use Apps\WordFrequencyCounter\Core\Repositories\WordsRepository;
use CommonF\Commands\CommandAbstract;
use Apps\WordFrequencyCounter\Core\Loggers\SimpleLogger;

class MigrateSQLite extends CommandAbstract
{
    protected WordsRepository $wordsRepository;
    protected SimpleLogger $simpleLogger;
    protected \SQLite3 $db;
    protected array $dbConfig;

    public function __construct(WordsRepository $wordsRepository, SimpleLogger $simpleLogger, $dbConfig, $options, $flags) {
        $this->wordsRepository = $wordsRepository;
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
            CREATE TABLE IF NOT EXISTS words_frequencies (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                word TEXT NOT NULL UNIQUE,
                frequency INTEGER NOT NULL DEFAULT 1
            )");

        $tableCheck = $this->db->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='words_frequencies'");

        $this->db->close();
        
        if (!$tableCheck) {
            $this->simpleLogger->error("Database initialization error: Table structure could not be built on disk."); exit(0);
        }

        $this->simpleLogger->log('Migrations were successfully done');
    }
}
