<?php

namespace CommonF\Repositories;

use CommonF\Interfaces\IRepository;

class SQLiteRepositoryAbstract implements IRepository
{
    public \SQLite3 $db;
    protected string $table;

    public function setDb(string $host) {
        $this->db = new \SQLite3($host);
        $this->db->enableExceptions(true);
    }

    public function getById(int $Id) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE id = :id
            ORDER BY id ASC
        ");

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        return $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    }

    public function existsBy(string $column, $value) {
        $stmt = $this->db->prepare("
            SELECT EXISTS(
                SELECT 1 FROM `{$this->table}` 
                WHERE `{$column}` = :v 
                LIMIT 1
            )
        ");

        $stmt->bindValue(':v', $value);

        return $stmt->execute()->fetchArray(SQLITE3_NUM)[0] > 0;
    }

    public function getAll(int $page = 1, int $perPage = 10) {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            ORDER BY id ASC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $perPage, SQLITE3_INTEGER);
        $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);

        return $stmt->execute();
    }
}
