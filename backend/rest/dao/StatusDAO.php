<?php
require_once 'BaseDAO.php';

class StatusDAO extends BaseDAO {
    public function __construct() {
        parent::__construct("Status", "StatusID");
    }

    public function createStatus($name) {
        return $this->create(['StatusName' => $name]);
    }

    public function getAllStatuses() {
        return $this->fetchAll("SELECT * FROM Status");
    }

    public function updateStatus($statusID, $name) {
        return $this->update($statusID, ['StatusName' => $name]);
    }

    public function deleteStatus($statusID) {
        return $this->delete($statusID);
    }
}
?>
