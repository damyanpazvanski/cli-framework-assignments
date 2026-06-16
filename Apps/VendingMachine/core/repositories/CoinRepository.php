<?php

namespace Apps\VendingMachine\Core\Repositories;

use CommonF\Repositories\SQLiteRepositoryAbstract;
use Apps\VendingMachine\Core\Entities\CoinEntity;

class CoinRepository extends SQLiteRepositoryAbstract
{
    protected string $table = 'possible_coins';

    public function setDbConfig(array $dbConfig) {
        $this->setDb($dbConfig['host'] . DIRECTORY_SEPARATOR . $dbConfig['file']);
    }

    public function getById(int $Id) {
        $result = parent::getById($id);

        return empty($result) ? null : new CoinEntity($result['id'], $result['price'] / 100);
    }

    public function existsByPrice($price): bool {
        return parent::existsBy('price', (int) ($price * 100));
    }

    public function getAll(int $page = 1, int $perPage = 10) {
        $results = parent::getAll($page, $perPage);

        $words = [];
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $words[] = new CoinEntity($row['id'], $row['price'] / 100);
        }

        $results->finalize();

        return $words;
    }

    public function insert(array $coin) {
        if (empty($coin)) return;
    
        $sql = "INSERT INTO {$this->table} (`price`) VALUES (:price)";

        // For validation
        $price = (int) ($coin['price'] * 100);

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':price', $price, SQLITE3_INTEGER);

        $stmt->execute();

        $lastId = $this->db->lastInsertRowID();

        return new CoinEntity($lastId, $price / 100);
    }

    public function delete($coinId) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $coinId, SQLITE3_INTEGER);

        $stmt->execute();

        return $this->db->changes() > 0;
    }
}
