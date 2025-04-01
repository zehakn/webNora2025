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
}
?>
