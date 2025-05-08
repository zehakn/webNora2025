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

    public function getByName($name) {
        $stmt = $this->connection->prepare("SELECT * FROM Priority WHERE PriorityLevel = :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIDByName($name) {
        $stmt = $this->connection->prepare("SELECT {$this->primaryKey} FROM {$this->tableName} WHERE PriorityLevel = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result[$this->primaryKey] : null;
    }

    public function getSingleByName($name) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->tableName WHERE PriorityLevel = :name LIMIT 1");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>
