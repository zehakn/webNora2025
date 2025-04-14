<?php
require_once 'BaseDAO.php';

class CategoryDAO extends BaseDAO {
    public function __construct() {
        parent::__construct("Category", "CategoryID");
    }

    public function createCategory($name) {
        return $this->create(['CategoryName' => $name]);
    }

    public function getAllCategories() {
        return $this->fetchAll("SELECT * FROM Category");
    }

    public function getCategoryById($categoryID) {
        return $this->getById($categoryID);
    }

    public function updateCategory($categoryID, $name) {
        return $this->update($categoryID, ['CategoryName' => $name]);
    }

    public function deleteCategory($categoryID) {
        return $this->delete($categoryID);
    }

    public function getByName($name) {
        $stmt = $this->connection->prepare("SELECT * FROM Category WHERE CategoryName = :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIDByName($name) {
        $stmt = $this->connection->prepare("SELECT {$this->primaryKey} FROM {$this->tableName} WHERE CategoryName = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result[$this->primaryKey] : null;
    }

    public function getSingleByName($name) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->tableName WHERE CategoryName = :name LIMIT 1");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
