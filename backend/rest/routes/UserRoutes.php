<?php
require_once __DIR__ . '/../services/UserService.php';

Flight::route('GET /test-users', function () {
    try {
        $userService = new UserService();
        $users = $userService->getAll();
        Flight::json($users);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('GET /test-user/@id', function ($id) {
    try {
        $userService = new UserService();
        $user = $userService->getById($id);
        Flight::json($user);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('GET /test-user-by-email/@email', function ($email) {
    try {
        $userService = new UserService();
        $user = $userService->getByEmail($email);
        Flight::json($user);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

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

Flight::route('DELETE /test-user/@id', function ($id) {
    try {
        $userService = new UserService();
        $userService->delete($id);
        Flight::json(['message' => 'User deleted']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});
