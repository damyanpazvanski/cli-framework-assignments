<?php

namespace CommonF\Repositories;

use CommonF\Interfaces\IRepository;

class SQLiteRepositoryAbstract implements IRepository
{
    public \SQLite3 $db;

    public function setDb(string $host) {
        $this->db = new \SQLite3($host);
        $this->db->enableExceptions(true);
    }
}
