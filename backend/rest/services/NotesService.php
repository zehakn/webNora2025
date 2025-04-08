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
        // Call the DAO's createNote method to insert a new note
        return $this->dao->createNote($userID, $title, $content, $categoryID, $statusID, $priorityID);
    }
}

?>
