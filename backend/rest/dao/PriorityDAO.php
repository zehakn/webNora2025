<?php
require_once 'BaseDAO.php';

class PriorityDAO extends BaseDAO {
    public function __construct() {
        parent::__construct("Priority", "PriorityID");
    }

    public function createPriority($name) {
        return $this->create(['PriorityLevel' => $name]);
    }

    public function getAllPriorities() {
        return $this->fetchAll("SELECT * FROM Priority");
    }

    public function updatePriority($priorityID, $name) {
        return $this->update($priorityID, ['PriorityLevel' => $name]);
    }

    public function deletePriority($priorityID) {
        return $this->delete($priorityID);
    }
}
?>
