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

    public function getByName($name) {
        $stmt = $this->connection->prepare("SELECT * FROM Status WHERE StatusName = :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIDByName($name) {
        $stmt = $this->connection->prepare("SELECT {$this->primaryKey} FROM {$this->tableName} WHERE StatusName = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result[$this->primaryKey] : null;
    }

    public function getSingleByName($name) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->tableName WHERE StatusName = :name LIMIT 1");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
