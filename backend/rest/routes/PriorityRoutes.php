<?php
require_once __DIR__ . '/../services/PriorityService.php';

Flight::route('GET /test-priorities', function() {
    try {
        $priorityService = new PriorityService();
        $priorities = $priorityService->getAll();
        Flight::json($priorities);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('GET /test-priority-by-name/@name', function ($name) {
    try {
        $priorityService = new PriorityService();
        $priority = $priorityService->getByName($name);
        if ($priority) {
            Flight::json($priority);
        } else {
            Flight::json(['error' => 'Priority not found'], 404);
        }
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('POST /test-priority', function () {
    try {
        $data = Flight::request()->data->getData();
        $priorityService = new PriorityService();
        $priorityService->create($data); 
        Flight::json(['message' => 'Priority created']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

Flight::route('PUT /test-priority/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData();
        $priorityService = new PriorityService();
        $priorityService->update($id, $data); 
        Flight::json(['message' => 'Priority updated']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

Flight::route('DELETE /test-priority/@id', function ($id) {
    try {
        $priorityService = new PriorityService();
        $priorityService->delete($id); 
        Flight::json(['message' => 'Priority deleted']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

?>
