<?php
require_once 'BaseDAO.php';

class NotesDAO extends BaseDAO {
    public function __construct() {
        parent::__construct("Notes", "NoteID");
    }

    public function getAllNotes() {
        return $this->fetchAll("SELECT * FROM Notes");
    }

    public function createNote($userID, $title, $content, $categoryID, $statusID, $priorityID) {
        $stmt = $this->connection->prepare(
            "INSERT INTO Notes (UserID, Title, Content, CategoryID, StatusID, PriorityID) 
            VALUES (:userID, :title, :content, :categoryID, :statusID, :priorityID)"
        );
        
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':categoryID', $categoryID);
        $stmt->bindParam(':statusID', $statusID);
        $stmt->bindParam(':priorityID', $priorityID);
        
        return $stmt->execute();
    }
    
    public function createNoteByNames($userID, $title, $content, $categoryName, $statusName, $priorityLevel) {
        $category = $this->getSingleByName($categoryName, 'Category');
        $status = $this->getSingleByName($statusName, 'Status');
        $priority = $this->getSingleByName($priorityLevel, 'Priority');
    
        if (!$category || !$status || !$priority) {
            throw new Exception("Invalid category, status, or priority name.");
        }
    
        return $this->createNote(
            $userID,
            $title,
            $content,
            $category['CategoryID'],
            $status['StatusID'],
            $priority['PriorityID']
        );
    }

    public function updateNote($noteID, $title, $content, $categoryID, $statusID, $priorityID) {
        return $this->update($noteID, [
            'Title' => $title,
            'Content' => $content,
            'CategoryID' => $categoryID,
            'StatusID' => $statusID,
            'PriorityID' => $priorityID
        ]);
    }

    public function getNoteByTitle($title) {
        $title = trim($title);
        $stmt = $this->connection->prepare("SELECT * FROM Notes WHERE LOWER(Title) = LOWER(?)");
        $stmt->execute([$title]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);   
    }

    public function deleteNote($noteID) {
        return $this->delete($noteID);
    }
}

?>