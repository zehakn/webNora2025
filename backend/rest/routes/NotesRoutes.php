<?php
require_once __DIR__ . '/../services/NotesService.php';

Flight::route('GET /test-notes', function () {
    try {
        $notesService = new NotesService();
        $notes = $notesService->getAll();
        Flight::json($notes);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('GET /test-note-by-title/@title', function ($title) {
    try {
        $notesService = new NotesService();
        $note = $notesService->getByTitle($title);
        
        if ($note) {
            Flight::json($note); 
        } else {
            Flight::json(['message' => 'Note not found'], 404); 
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('POST /add-note', function () {
    try {
        
        $userID = Flight::request()->data->userID;
        $title = Flight::request()->data->title;
        $content = Flight::request()->data->content;
        $categoryID = Flight::request()->data->categoryID;
        $statusID = Flight::request()->data->statusID;
        $priorityID = Flight::request()->data->priorityID;

        $notesService = new NotesService();
        $result = $notesService->add($userID, $title, $content, $categoryID, $statusID, $priorityID);

        if ($result) {
            Flight::json(['success' => true, 'message' => 'Note successfully added.']);
        } else {
            Flight::json(['success' => false, 'message' => 'Failed to add note.'], 400);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});


Flight::route('PUT /test-note/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();

        if (!isset($data['Title'], $data['Content'], $data['CategoryName'], $data['StatusName'], $data['PriorityName'])) {
            throw new Exception("Missing required fields.");
        }

        $notesService = new NotesService();
        $notesService->updateNoteByNames(
            $id,
            $data['Title'],
            $data['Content'],
            $data['CategoryName'],
            $data['StatusName'],
            $data['PriorityName']
        );

        Flight::json(['message' => 'Note updated']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

Flight::route('DELETE /test-note/@id', function ($id) {
    try {
        $notesService = new NotesService();
        $deleted = $notesService->deleteNote($id);
        if ($deleted === 0) {
            throw new Exception("Note with ID $id does not exist.");
        }
        Flight::json(['message' => 'Note deleted']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});
