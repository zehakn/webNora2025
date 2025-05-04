<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CategoryDao.php';

class CategoryService extends BaseService {
    public function __construct() {
        parent::__construct(new CategoryDao());
    }

    public function create($data) {
        if (empty($data['CategoryName'])) {
            throw new Exception("Category name cannot be empty.");
        }
        return $this->dao->createCategory($data['CategoryName']);  
    }

    public function getAll() {
        return $this->dao->getAllCategories();
    }

    public function getByName($name) {
        $category = $this->dao->getByName($name);
        if ($category) {
            return $category;
        } else {
            throw new Exception("Category not found.");
        }
    }

    public function getById($id) {
        $category = $this->dao->getById($id);
        if (!$category) {
            throw new Exception("Category not found.");
        }
        return $category;
    }


    public function update($id, $data) {
        if (empty($data['CategoryName'])) {	
            throw new Exception("Category name cannot be empty.");
        }
        return $this->dao->updateCategory($id, $data['CategoryName']);
    }

    public function delete($id) {
        $category = $this->dao->getById($id);
        if (!$status) {
            throw new Exception("Category not found.");
        }
        return $this->dao->deleteCategory($id);
    }
}
?>
