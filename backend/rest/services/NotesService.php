<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/NotesDao.php';
require_once __DIR__ . '/UserService.php';
require_once __DIR__ . '/CategoryService.php';
require_once __DIR__ . '/PriorityService.php';
require_once __DIR__ . '/StatusService.php';

class NotesService extends BaseService {
    public function __construct() {
        parent::__construct(new NotesDao());
    }

    public function getAll() {
        $notes = $this->dao->getAllNotes();
        
        foreach ($notes as &$note) {
            // Fetch related entities for each note and add to the note
            $note['User'] = $this->getUserById($note['UserID']);
            $note['Category'] = $this->getCategoryById($note['CategoryID']);
            $note['Priority'] = $this->getPriorityById($note['PriorityID']);
            $note['Status'] = $this->getStatusById($note['StatusID']);
        }

        return $notes;
    }

    private function getUserById($userId) {
        if (!$userId) return null;
        $userService = new UserService();
        return $userService->getById($userId);
    }

    private function getCategoryById($categoryId) {
        if (!$categoryId) return null;
        $categoryService = new CategoryService();
        return $categoryService->getById($categoryId);
    }

    private function getPriorityById($priorityId) {
        if (!$priorityId) return null;
        $priorityService = new PriorityService();
        return $priorityService->getById($priorityId);
    }

    private function getStatusById($statusId) {
        if (!$statusId) return null;
        $statusService = new StatusService();
        return $statusService->getById($statusId);
    }

    public function add($userID, $title, $content, $categoryID, $statusID, $priorityID) {
        
        return $this->dao->createNote($userID, $title, $content, $categoryID, $statusID, $priorityID);
    }

    public function addNoteByNames($userID, $title, $content, $categoryName, $statusName, $priorityLevel) {
        $categoryDAO = new CategoryDAO();
        $statusDAO = new StatusDAO();
        $priorityDAO = new PriorityDAO();
    
        $category = $categoryDAO->getSingleByName($categoryName);
        $status = $statusDAO->getSingleByName($statusName);
        $priority = $priorityDAO->getSingleByName($priorityLevel);
    
        if (!$category || !$status || !$priority) {
            throw new Exception("Invalid category, status, or priority name.");
        }
    
        return $this->dao->createNote(
            $userID,
            $title,
            $content,
            $category['CategoryID'],
            $status['StatusID'],
            $priority['PriorityID']
        );
    }

    public function getByTitle($title) {
        $note = $this->dao->getNoteByTitle($title);
        if ($note) {
            return $note;
        } else {
            throw new Exception("Note with title '$title' not found.");
        }
    }

    public function updateNoteByNames($noteID, $title, $content, $categoryName, $statusName, $priorityName) {
        $categoryDAO = new CategoryDAO();
        $statusDAO = new StatusDAO();
        $priorityDAO = new PriorityDAO();
        $notesDAO = new NotesDAO();
    
        $categoryID = $categoryDAO->getIDByName($categoryName);
        $statusID = $statusDAO->getIDByName($statusName);
        $priorityID = $priorityDAO->getIDByName($priorityName);
    
        if ($categoryID === null || $statusID === null || $priorityID === null) {
            throw new Exception("Invalid category, status, or priority name provided.");
        }
    
        return $notesDAO->updateNote($noteID, $title, $content, $categoryID, $statusID, $priorityID);
    }

    public function deleteNote($id) {
        return $this->dao->deleteNote($id);
    }
}

?>
