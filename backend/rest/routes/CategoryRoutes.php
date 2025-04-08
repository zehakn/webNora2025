<?php
require_once __DIR__ . '/../services/CategoryService.php';

Flight::route('GET /test-categories', function() {
    try {
        $categoryService = new CategoryService();
        $categories = $categoryService->getAll();
        Flight::json($categories);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('GET /test-category-by-name/@name', function ($name) {
    try {
        $categoryService = new CategoryService();
        $category = $categoryService->getByName($name); 
        Flight::json($category);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

Flight::route('POST /test-category', function () {
    try {
        $data = Flight::request()->data->getData(); 
        $categoryService = new CategoryService();
        $categoryService->create($data); 
        Flight::json(['message' => 'Category created']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

Flight::route('PUT /test-category/@id', function ($id) {
    try {
        $data = Flight::request()->data->getData(); 
        $categoryService = new CategoryService();
        $categoryService->update($id, $data); 
        Flight::json(['message' => 'Category updated']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

Flight::route('DELETE /test-category/@id', function ($id) {
    try {
        $categoryService = new CategoryService();
        $categoryService->delete($id); 
        Flight::json(['message' => 'Category deleted']);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

?>
