<?php
require_once __DIR__ . '/../services/StatusService.php';

/**
 * @OA\Get(
 *     path="/test-statuses",
 *     summary="Get all statuses",
 *     tags={"Statuses"},
 *     @OA\Response(
 *         response=200,
 *         description="List of statuses",
 *         @OA\JsonContent(type="array", @OA\Items(type="object"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
Flight::route('GET /test-statuses', function() {
    try {
        $statusService = new StatusService();
        $statuses = $statusService->getAll();
        Flight::json($statuses);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Get(
 *     path="/test-status-by-name/{name}",
 *     summary="Get a status by name",
 *     tags={"Statuses"},
 *     @OA\Parameter(
 *         name="name",
 *         in="path",
 *         required=true,
 *         description="Name of the status",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Status details",
 *         @OA\JsonContent(type="object")
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
Flight::route('GET /test-status-by-name/@name', function ($name) {
    try {
        $statusService = new StatusService();
        $status = $statusService->getByName($name); 
        Flight::json($status);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Post(
 *     path="/test-status",
 *     summary="Create a new status",
 *     tags={"Statuses"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(type="object")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Status created successfully",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     )
 * )
 */
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

/**
 * @OA\Put(
 *     path="/test-status/{id}",
 *     summary="Update an existing status",
 *     tags={"Statuses"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the status",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(type="object")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Status updated successfully",
 *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string"))
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     )
 * )
 */
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

/**
 * @OA\Delete(
 *     path="/test-status/{id}",
 *     summary="Delete a status",
 *     tags={"Statuses"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the status to delete",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Status deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Status deleted")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Status not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Status not found")
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
Flight::route('DELETE /test-status/@id', function ($id) {
    try {
        $statusService = new StatusService();
        $status = $statusService->getById($id);

        if (!$status) {
            Flight::json(['error' => 'Status not found'], 404);
            return;
        }

        $statusService->delete($id); 
        Flight::json(['message' => 'Status deleted']);
        
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

?>
