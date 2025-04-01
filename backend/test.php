<?php
require_once 'rest/dao/UserDAO.php';
require_once 'rest/dao/CategoryDAO.php';
require_once 'rest/dao/StatusDAO.php';
require_once 'rest/dao/PriorityDAO.php';
require_once 'rest/dao/NotesDAO.php';

try {
    echo "<h2>Testing UserDAO</h2>";
    $userDAO = new UserDAO();
    $randomString = substr(md5(mt_rand()), 0, 6);
    $email = "test_" . $randomString . "@example.com";
    $userID = $userDAO->createUser("user_" . $randomString, "password123", "Test User " . $randomString, $email);
    echo "<p><strong>User Created:</strong> ID = $userID</p>";
    echo "<pre>" . json_encode($userDAO->getAllUsers(), JSON_PRETTY_PRINT) . "</pre>";
    $userDAO->updateUser($userID, "updated_user", "Updated Test User", "updated_" . $email);
    echo "<p><strong>Updated User:</strong></p><pre>" . json_encode($userDAO->getByEmail("updated_" . $email), JSON_PRETTY_PRINT) . "</pre>";
    $userDAO->deleteUser($userID);
    echo "<p><strong>User Deleted</strong></p>";
    
    echo "<h2>Testing CategoryDAO</h2>";
    $categoryDAO = new CategoryDAO();
    $categoryID = $categoryDAO->createCategory("Test Category " . $randomString);
    echo "<p><strong>Category Created:</strong> ID = $categoryID</p>";
    echo "<pre>" . json_encode($categoryDAO->getAllCategories(), JSON_PRETTY_PRINT) . "</pre>";
    $categoryDAO->updateCategory($categoryID, "Updated Category");
    $categoryDAO->deleteCategory($categoryID);
    echo "<p><strong>Category Deleted</strong></p>";
    
    echo "<h2>Testing StatusDAO</h2>";
    $statusDAO = new StatusDAO();
    $statusID = $statusDAO->createStatus("Test Status " . $randomString);
    echo "<p><strong>Status Created:</strong> ID = $statusID</p>";
    echo "<pre>" . json_encode($statusDAO->getAllStatuses(), JSON_PRETTY_PRINT) . "</pre>";
    $statusDAO->updateStatus($statusID, "Updated Status");
    $statusDAO->deleteStatus($statusID);
    echo "<p><strong>Status Deleted</strong></p>";
    
    echo "<h2>Testing PriorityDAO</h2>";
    $priorityDAO = new PriorityDAO();
    $priorityID = $priorityDAO->createPriority("Test Priority " . $randomString);
    echo "<p><strong>Priority Created:</strong> ID = $priorityID</p>";
    echo "<pre>" . json_encode($priorityDAO->getAllPriorities(), JSON_PRETTY_PRINT) . "</pre>";
    $priorityDAO->updatePriority($priorityID, "Updated Priority");
    $priorityDAO->deletePriority($priorityID);
    echo "<p><strong>Priority Deleted</strong></p>";
    
    echo "<h2>Testing NotesDAO</h2>";
    $userID = 56;
    $categoryID = 48;
    $statusID = 48;
    $priorityID = 47;
    $notesDAO = new NotesDAO();
    echo "<p><strong>Note Metadata:</strong></p>";
    echo "<pre>UserID: $userID, CategoryID: $categoryID, StatusID: $statusID, PriorityID: $priorityID</pre>";
    $noteID = $notesDAO->createNote($userID, "Test Note", "This is a test note", $categoryID, $statusID, $priorityID);
    echo "<p><strong>Note Created:</strong> ID = $noteID</p>";
    echo "<pre>" . json_encode($notesDAO->getAllNotes(), JSON_PRETTY_PRINT) . "</pre>";
    $notesDAO->updateNote($noteID, "Updated Note", "Updated content", $categoryID, $statusID, $priorityID);
    echo "<p><strong>Note Updated</strong></p>";
    $notesDAO->deleteNote($noteID);
    echo "<p><strong>Note Deleted</strong></p>";
} catch (Exception $e) {
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>
