<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PriorityDao.php';

class PriorityService extends BaseService {
    public function __construct() {
        parent::__construct(new PriorityDAO());
    }

    public function create($data) {
        if (empty($data['PriorityLevel'])) {
            throw new Exception("Priority level is required.");
        }
        return $this->dao->createPriority($data['PriorityLevel']);  
    }

    public function getAll() {
        return $this->dao->getAllPriorities();
    }

    public function getByName($name) {
        $priority = $this->dao->getByName($name);
        if ($priority) {
            return $priority;
        } else {
            throw new Exception("Priority not found.");
        }
    }

    public function update($id, $data) {
        if (empty($data['PriorityLevel'])) {
            throw new Exception("Priority level is required.");
        }
        return $this->dao->updatePriority($id, $data['PriorityLevel']);
    }

    public function delete($id) {
        return $this->dao->deletePriority($id);
    }
}
?>
