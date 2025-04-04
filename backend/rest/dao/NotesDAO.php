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
        return $this->create([
            'UserID' => $userID,
            'Title' => $title,
            'Content' => $content,
            'CategoryID' => $categoryID,
            'StatusID' => $statusID,
            'PriorityID' => $priorityID,
            
        ]);
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

    public function deleteNote($noteID) {
        return $this->delete($noteID);
    }
}

?>
