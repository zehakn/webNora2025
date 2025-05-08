<?php
require_once __DIR__ . '/../services/CategoryService.php';

/**
 * @OA\Get(
 *     path="/test-categories",
 *     summary="Get all categories",
 *     tags={"Categories"},
 *     @OA\Response(
 *         response=200,
 *         description="List of categories"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
Flight::route('GET /test-categories', function() {
    try {
        $categoryService = new CategoryService();
        $categories = $categoryService->getAll();
        Flight::json($categories);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Get(
 *     path="/test-category-by-name/{name}",
 *     summary="Get a category by name",
 *     tags={"Categories"},
 *     @OA\Parameter(
 *         name="name",
 *         in="path",
 *         required=true,
 *         description="Name of the category",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category details"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
Flight::route('GET /test-category-by-name/@name', function ($name) {
    try {
        $categoryService = new CategoryService();
        $category = $categoryService->getByName($name); 
        Flight::json($category);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Post(
 *     path="/test-category",
 *     summary="Create a new category",
 *     tags={"Categories"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", description="Name of the category")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     )
 * )
 */
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

/**
 * @OA\Put(
 *     path="/test-category/{id}",
 *     summary="Update an existing category",
 *     tags={"Categories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the category",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", description="Updated name of the category")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     )
 * )
 */
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

/**
 * @OA\Delete(
 *     path="/test-category/{id}",
 *     summary="Delete a category",
 *     tags={"Categories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the category",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 */

Flight::route('DELETE /test-category/@id', function ($id) {
    try {
        $categoryService = new CategoryService();
        $category = $categoryService->getById($id);

        if (!$category) {
            Flight::json(['error' => 'Category not found'], 404);
            return;
        }

        $categoryService->delete($id);
        Flight::json(['message' => 'Category deleted']);
        
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

?>
