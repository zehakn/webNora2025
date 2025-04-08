<?php
require_once __DIR__ . '/../services/StatusService.php';


Flight::route('GET /test-statuses', function() {
    try {
        $statusService = new StatusService();
        $statuses = $statusService->getAll();
        Flight::json($statuses);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('GET /test-status-by-name/@name', function ($name) {
    try {
        $statusService = new StatusService();
        $status = $statusService->getByName($name); 
        Flight::json($status);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('POST /test-status', function () {
    try {
        $data = Flight::request()->data->getData(); 
        $statusService = new StatusService();
        $statusService->create($data); 
        Flight::json(['message' => 'Status created']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

Flight::route('PUT /test-status/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData(); 
        $statusService = new StatusService();
        $statusService->update($id, $data); 
        Flight::json(['message' => 'Status updated']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

Flight::route('DELETE /test-status/@id', function ($id) {
    try {
        $statusService = new StatusService();
        $statusService->delete($id); 
        Flight::json(['message' => 'Status deleted']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

?>
