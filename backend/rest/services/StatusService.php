<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/StatusDao.php';

class StatusService extends BaseService {
    
    public function __construct() {
        parent::__construct(new StatusDAO());
    }

    public function create($data) {
        if (empty($data['StatusName'])) {
            throw new Exception("Status label is required.");
        }
        return $this->dao->createStatus($data['StatusName']); 
    }

    public function getAll() {
        return $this->dao->getAllStatuses();
    }

    public function getByName($name) {
        $status = $this->dao->getByName($name);
        if (!$status) {
            throw new Exception("Status not found.");
        }
        return $status;
    }

    public function getById($id) {
        $status = $this->dao->getById($id);
        if (!$status) {
            throw new Exception("Status not found.");
        }
        return $status;
    }

    public function update($id, $data) {
        if (empty($data['StatusName'])) {
            throw new Exception("Status label is required.");
        }
        return $this->dao->updateStatus($id, $data['StatusName']);
    }

    public function delete($id) {
        
        $status = $this->dao->getById($id);
        if (!$status) {
            throw new Exception("Status not found.");
        }
        return $this->dao->deleteStatus($id);
    }
}
?>
