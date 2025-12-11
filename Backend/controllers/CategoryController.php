<?php

require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/User.php';

class CategoryController extends ApplicationController {

    // CREATE
    // Required: name, user_id

    public function createAction() {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(["success" => false, "error" => "Method not allowed"]);
        }

        $name = isset($_POST['name']) ? trim($_POST['name']) : null;
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
        $description = isset($_POST['description']) ? trim($_POST['description']) : null;

        if (empty($name)) {
            return $this->json(["success" => false, "error" => "Category name is required"]);
        }

        if (empty($user_id)) {
            return $this->json(["success" => false, "error" => "User_id is required"]);
        }

        // // Verify user exists
        // $userModel = new User();
        // $user = $userModel->findById($user_id);

        // if (!$user) {
        //     return $this->json(["success" => false, "error" => "User not found"]);
        // }

        // Create $data array
        $data = [
            'user_id' => $user_id
        ];

        if ($description !== null && $description !== '') {
            $data['description'] = $description;
        }

        $categoryModel = new Category();
        $newCategory = $categoryModel->createCategory($name, $data);

        return $this->json([
            "success" => true,
            "message" => "Category created successfully",
            "data" => $newCategory
        ]);
    }


    // READ - Get all categories

    public function indexAction() {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return $this->json(["success" => false, "error" => "Method not allowed"]);
        }

        $categoryModel = new Category();

        // Check for filter parameters
        $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

        // Apply filter if user_id parameter is provided
        if ($user_id !== null) {
            $categories = $categoryModel->filterByUserId($user_id);
        } else {
        // No filters - return all tasks
            $categories = $categoryModel->readAllCategories();
        }

        return $this->json([
            "success" => true,
            "count" => count($categories),
            "data" => $categories
        ]);
    }

    // READ - (specific category by ID)
    // Required: id parameter in URL
    
    public function showAction() {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return $this->json(["success" => false, "error" => "Method not allowed"]);
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            return $this->json(["success" => false, "error" => "Category ID is required"]);
        }

        $categoryModel = new Category();
        $category = $categoryModel->findByCategoryId($id);

        if (!$category) {
            return $this->json(["success" => false, "error" => "Category not found"]);
        }

        return $this->json([
            "success" => true,
            "data" => $category
        ]);
    }
}

?>
