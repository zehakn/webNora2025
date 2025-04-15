<?php
require_once __DIR__ . '/../services/NotesService.php';

/**
 * @OA\Get(
 *     path="/test-notes",
 *     summary="Get all notes",
 *     tags={"Notes"},
 *     @OA\Response(
 *         response=200,
 *         description="List of notes"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
Flight::route('GET /test-notes', function () {
    try {
        $notesService = new NotesService();
        $notes = $notesService->getAll();
        Flight::json($notes);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Get(
 *     path="/test-note-by-title/{title}",
 *     summary="Get a note by title",
 *     tags={"Notes"},
 *     @OA\Parameter(
 *         name="title",
 *         in="path",
 *         required=true,
 *         description="Title of the note",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Note details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Note not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
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

/**
 * @OA\Post(
 *     path="/add-note",
 *     summary="Create a new note",
 *     tags={"Notes"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="userID", type="integer", description="ID of the user"),
 *             @OA\Property(property="title", type="string", description="Title of the note"),
 *             @OA\Property(property="content", type="string", description="Content of the note"),
 *             @OA\Property(property="categoryID", type="integer", description="ID of the category"),
 *             @OA\Property(property="statusID", type="integer", description="ID of the status"),
 *             @OA\Property(property="priorityID", type="integer", description="ID of the priority")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Note created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
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

/**
 * @OA\Put(
 *     path="/test-note/{id}",
 *     summary="Update an existing note",
 *     tags={"Notes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the note",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="Title", type="string", description="Updated title of the note"),
 *             @OA\Property(property="Content", type="string", description="Updated content of the note"),
 *             @OA\Property(property="CategoryName", type="string", description="Updated category name"),
 *             @OA\Property(property="StatusName", type="string", description="Updated status name"),
 *             @OA\Property(property="PriorityName", type="string", description="Updated priority name")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Note updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Note not found"
 *     )
 * )
 */
Flight::route('PUT /test-note/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();

        if (!isset($data['Title'], $data['Content'], $data['CategoryName'], $data['StatusName'], $data['PriorityName'])) {
            throw new Exception("Missing required fields.");
        }

        $notesService = new NotesService();

        $existingNote = $notesService->getById($id);
        if (!$existingNote) {
            Flight::json(['error' => 'Note not found.'], 404);
            return;
        }

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

/**
 * @OA\Delete(
 *     path="/test-note/{id}",
 *     summary="Delete a note",
 *     tags={"Notes"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the note",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Note deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Note not found"
 *     )
 * )
 */
Flight::route('DELETE /test-note/@id', function ($id) {
    try {
        $notesService = new NotesService();
        $note = $notesService->getById($id);

        if (!$note) {
            Flight::json(['error' => "Note with ID $id not found."], 404);
            return;
        }

        $notesService->deleteNote($id);
        Flight::json(['message' => 'Note deleted']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

