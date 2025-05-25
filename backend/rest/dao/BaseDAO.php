<?php
require_once __DIR__ . '/../../config.php';

class BaseDAO {

    protected $tableName;
    protected $connection;
    protected $primaryKey; 
 
 
    public function __construct($tableName) {
        $this->tableName = $tableName;
        $this->connection = Database::connect();
        $this->primaryKey = $this->detectPrimaryKey(); 
    }
        
    private function detectPrimaryKey() {
        $stmt = $this->connection->prepare("SHOW KEYS FROM $this->tableName WHERE Key_name = 'PRIMARY'");
        $stmt->execute();
        $primaryKeyData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $primaryKeyData ? $primaryKeyData['Column_name'] : 'id'; 
    }


    public function fetchAll() {
        $stmt = $this->connection->prepare("SELECT * FROM $this->tableName");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM $this->tableName WHERE $this->primaryKey = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $stmt = $this->connection->prepare("INSERT INTO $this->tableName ($columns) VALUES ($values)");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $this->connection->lastInsertId();
    }

  
    public function update($id, $data) {
        $fields = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $stmt = $this->connection->prepare("UPDATE $this->tableName SET $fields WHERE $this->primaryKey = :id");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(":id", $id);
        return $stmt->execute(); 
    }


    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM $this->tableName WHERE $this->primaryKey = :id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

   
    public function countAll() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM $this->tableName");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getByField($field, $value) {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->tableName} WHERE {$field} = :value");
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>