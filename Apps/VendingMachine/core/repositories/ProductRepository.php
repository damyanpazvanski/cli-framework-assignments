<?php

namespace Apps\VendingMachine\Core\Repositories;

use CommonF\Repositories\SQLiteRepositoryAbstract;
use Apps\VendingMachine\Core\Entities\ProductEntity;

class ProductRepository extends SQLiteRepositoryAbstract
{
    protected string $table = 'products';

    public function setDbConfig(array $dbConfig) {
        $this->setDb($dbConfig['host'] . DIRECTORY_SEPARATOR . $dbConfig['file']);
    }

    public function getById(int $Id) {
        $result = parent::getById($id);

        return empty($result) ? null : new ProductEntity($result['id'], $result['name'], $result['price'] / 100);
    }

    public function existsByName(string $name): bool {
        return parent::existsBy('name', $name);
    }

    public function getByName(string $value) {
        $stmt = $this->db->prepare("
            SELECT * FROM `{$this->table}` 
            WHERE `name` = :v
            LIMIT 1
        ");

        $stmt->bindValue(':v', $value);
        $product = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        return empty($product) ? null : new ProductEntity($product['id'], $product['name'], $product['price'] / 100);
    }

    public function getAll(int $page = 1, int $perPage = 10) {
        $results = parent::getAll($page, $perPage);

        $words = [];
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $words[] = new ProductEntity($row['id'], $row['name'], $row['price'] / 100);
        }

        $results->finalize();

        return $words;
    }

    public function insert(array $product) {
        if (empty($product)) return;
    
        $sql = "INSERT INTO {$this->table} (`name`, `price`) VALUES (:pName, :price)";

        // For validation
        $price = (int) ($product['price'] * 100);
        
        $stmt = $this->db->prepare($sql);    
        $stmt->bindValue(':pName', $product['name'], SQLITE3_TEXT);
        $stmt->bindValue(':price', $price, SQLITE3_INTEGER);

        $stmt->execute();

        $lastId = $this->db->lastInsertRowID();

        return new ProductEntity($lastId, $product['name'], $price / 100);
    }

    public function delete($coinId) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $coinId, SQLITE3_INTEGER);

        $stmt->execute();

        return $this->db->changes() > 0;
    }
}
