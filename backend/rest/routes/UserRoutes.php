<?php
require_once __DIR__ . '/../services/UserService.php';

/**
 * @OA\Get(
 *     path="/test-users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="List of users"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
Flight::route('GET /test-users', function () {
    try {
        $userService = new UserService();
        $users = $userService->getAll();
        Flight::json($users);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Get(
 *     path="/test-user/{id}",
 *     summary="Get a user by ID",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User details"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
Flight::route('GET /test-user/@id', function ($id) {
    try {
        $userService = new UserService();
        $user = $userService->getById($id);
        Flight::json($user);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Get(
 *     path="/test-user-by-email/{email}",
 *     summary="Get a user by email",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="email",
 *         in="path",
 *         required=true,
 *         description="Email of the user",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User details"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
Flight::route('GET /test-user-by-email/@email', function ($email) {
    try {
        $userService = new UserService();
        $user = $userService->getByEmail($email);
        Flight::json($user);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Post(
 *     path="/test-user",
 *     summary="Create a new user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="email", type="string", description="Email of the user"),
 *             @OA\Property(property="username", type="string", description="Username of the user"),
 *             @OA\Property(property="fullName", type="string", description="Full name of the user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     )
 * )
 */
Flight::route('POST /test-user', function () {
    try {
   
        $data = Flight::request()->data->getData(); 

        $userService = new UserService();
        $userId = $userService->create($data);

        Flight::json(['message' => 'User created', 'user_id' => $userId]);
    } catch (Exception $e) {
        
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Put(
 *     path="/test-user/{id}",
 *     summary="Update an existing user",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="email", type="string", description="Updated email of the user"),
 *             @OA\Property(property="username", type="string", description="Updated username of the user"),
 *             @OA\Property(property="fullName", type="string", description="Updated full name of the user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     )
 * )
 */
Flight::route('PUT /test-user/@id', function ($id) {
    try {
        
        $data = Flight::request()->data->getData();

        if (empty($data['email']) || empty($data['username']) || empty($data['fullName'])) {
            throw new Exception("Email, username, and full name are required.");
        }

        $userService = new UserService();
        $userService->update($id, $data);

        Flight::json(['message' => 'User updated']);
    } catch (Exception $e) {

        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Delete(
 *     path="/test-user/{id}",
 *     summary="Delete a user",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="User deleted")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="User not found")
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
Flight::route('DELETE /test-user/@id', function ($id) {
    try {
        $userService = new UserService();
        $user = $userService->getById($id);

        if (!$user) {
            Flight::json(['error' => 'User not found'], 404);
            return;
        }

        $userService->delete($id);
        Flight::json(['message' => 'User deleted']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});
