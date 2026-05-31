<?php

namespace Apps\WordFrequencyCounter\Core\Repositories;

use CommonF\Repositories\SQLiteRepositoryAbstract;
use Apps\WordFrequencyCounter\Core\Entities\WordCounter;

class WordsRepository extends SQLiteRepositoryAbstract
{
    protected $wordsFrequencyTable = 'words_frequencies';

    // Reuse the statement for better performance
    private ?\SQLite3Stmt $insertWordStmt = null;

    public function setDbConfig(array $dbConfig) {
        $this->setDb($dbConfig['host'] . DIRECTORY_SEPARATOR . $dbConfig['file']);
    }

    public function getWordById(int $wordId) {
        $query = $this->db->prepare("
            SELECT * FROM {$this->wordsFrequencyTable}
            WHERE id = :id
            ORDER BY id ASC
        ");

        $query->bindValue(':id', $wordId, SQLITE3_INTEGER);

        $result = $query->execute()->fetchArray(SQLITE3_ASSOC);

        return empty($result) ? [] : new WordCounter($result['id'], $result['word'], $result['frequency']);
    }

    public function getWords(int $page = 1, int $perPage) {
        $offset = ($page - 1) * $perPage;

        $query = $this->db->prepare("
            SELECT * FROM {$this->wordsFrequencyTable}
            ORDER BY id ASC
            LIMIT :limit OFFSET :offset
        ");

        $query->bindValue(':limit', $perPage, SQLITE3_INTEGER);
        $query->bindValue(':offset', $offset, SQLITE3_INTEGER);

        $results = $query->execute();

        $words = [];
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $words[] = new WordCounter($row['id'], $row['word'], $row['frequency']);
        }

        $results->finalize();

        return $words;
    }

    public function getPagesCount(int $perPage) {
        $totalRows = $this->db->querySingle("SELECT COUNT(*) FROM {$this->wordsFrequencyTable}");
        return (int) ceil($totalRows / $perPage);
    }

    public function updateCountersForWords(array $wordBatch) {
        if (empty($wordBatch)) return;
    
        $this->attachInsertWordStmt();
    
        // TRANSACTION for better performance
        $this->db->exec('BEGIN TRANSACTION');
    
        try {
            foreach ($wordBatch as $word) {
                $this->insertWordStmt->bindValue(':word', $word, SQLITE3_TEXT);
                $this->insertWordStmt->execute();
                $this->insertWordStmt->reset();
            }

            $this->db->exec('COMMIT');
        } catch (Exception $e) {
            $this->db->exec('ROLLBACK');
            throw $e;
        }
    }

    protected function attachInsertWordStmt() {
        if (!$this->insertWordStmt) {
            $sql = "INSERT INTO {$this->wordsFrequencyTable} (word)
                VALUES (:word)
                ON CONFLICT(word) DO UPDATE SET frequency = frequency + 1";

            $this->insertWordStmt = $this->db->prepare($sql);
        }
    }
}
