<?php
require_once __DIR__ . '/../services/PriorityService.php';

/**
 * @OA\Get(
 *     path="/test-priorities",
 *     summary="Get all priorities",
 *     tags={"Priorities"},
 *     @OA\Response(
 *         response=200,
 *         description="List of priorities",
 *         @OA\JsonContent(type="array", @OA\Items(type="object"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
Flight::route('GET /test-priorities', function() {
    try {
        $priorityService = new PriorityService();
        $priorities = $priorityService->getAll();
        Flight::json($priorities);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Get(
 *     path="/test-priority-by-name/{name}",
 *     summary="Get a priority by name",
 *     tags={"Priorities"},
 *     @OA\Parameter(
 *         name="name",
 *         in="path",
 *         required=true,
 *         description="Name of the priority",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Priority details",
 *         @OA\JsonContent(type="object")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Priority not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
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

/**
 * @OA\Post(
 *     path="/test-priority",
 *     summary="Create a new priority",
 *     tags={"Priorities"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(type="object")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Priority created successfully",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     )
 * )
 */
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

/**
 * @OA\Put(
 *     path="/test-priority/{id}",
 *     summary="Update an existing priority",
 *     tags={"Priorities"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the priority to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(type="object")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Priority updated successfully",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     )
 * )
 */
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

/**
 * @OA\Delete(
 *     path="/test-priority/{id}",
 *     summary="Delete a priority",
 *     tags={"Priorities"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the priority to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Priority deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Priority deleted")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Priority not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Priority not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Some error message")
 *         )
 *     )
 * )
 */
Flight::route('DELETE /test-priority/@id', function ($id) {
    try {
        $priorityService = new PriorityService();
        $priority = $priorityService->getById($id);

        if (!$priority) {
            Flight::json(['error' => 'Priority not found'], 404);
            return;
        }

        $priorityService->delete($id);
        Flight::json(['message' => 'Priority deleted']);
        
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

?>
