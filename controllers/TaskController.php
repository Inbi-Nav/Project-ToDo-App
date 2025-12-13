<?php

require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';

class TaskController extends ApplicationController{ 
   
    // CREATE
    // Required: title, user_id


   public function createAction() {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return $this->json(["success" => false, "error" => "Method not allowed"]);
    }

    $title = trim($_POST['title'] ?? '');
    $user_id = $_POST['user_id'] ?? null;
    $description = trim($_POST['description'] ?? '');
    $category_id = $_POST['category_id'] ?? null;
    $start_at = $_POST['start_at'] ?? null;
    $end_at = $_POST['end_at'] ?? null;

    if (empty($title)) {
        return $this->json(["success" => false, "error" => "Title is required"]);
    }

    if (empty($user_id)) {
        return $this->json(["success" => false, "error" => "User_id is required"]);
    }

    // Validate user exists
    $userModel = new User();
    $user = $userModel->findById($user_id);

    if (!$user) {
        return $this->json(["success" => false, "error" => "User not found"]);
    }

    // Validate category if provided
    if (!empty($category_id)) {
        $categoryModel = new Category();
        $category = $categoryModel->findByCategoryId($category_id);

        if (!$category) {
            return $this->json(["success" => false, "error" => "Category not found"]);
        }
    }

    // Build task data
    $data = [
        'user_id' => $user_id,
        'description' => $description
    ];

    if ($category_id !== null && $category_id !== '') {
        $data['category_id'] = $category_id;
    }

    if ($start_at !== null && $start_at !== '') {
        $data['start_at'] = $start_at;
    }

    if ($end_at !== null && $end_at !== '') {
        $data['end_at'] = $end_at;
    }

    $taskModel = new Task();
    $result = $taskModel->createTask($title, $data);

    return $this->json(["success" => true, "data" => $result]);
}



    // READ - (all Tasks or by filter paramenter)
    // Optional query params: user_id, status, category_id

    public function indexAction() {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return $this->json(["success" => false, "error" => "Method not allowed"]);
        }

        $taskModel = new Task();
        
        // Check for filter parameters
        $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
        $status = isset($_GET['status']) ? trim($_GET['status']) : null;
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

        // Apply filters if parameters are provided
        if ($user_id !== null) {
            $tasks = $taskModel->filterByUserId($user_id);
        } elseif ($status !== null) {
            $tasks = $taskModel->filterByStatus($status);
        } elseif ($category_id !== null) {
            $tasks = $taskModel->filterByCategoryId($category_id);
        } else {
        // No filters - return all tasks
            $tasks = $taskModel->readAllTasks();
        }

        return $this->json([
            "success" => true,
            "count" => count($tasks),
            "data" => $tasks
        ]);
    }

    // READ - (specific task by ID)
    // Required: id parameter in URL
    public function showAction() {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return $this->json(["success" => false, "error" => "Method not allowed"]);
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            return $this->json(["success" => false, "error" => "Task ID is required"]);
        }

        $taskModel = new Task();
        $task = $taskModel->filterByTask($id);

        if (empty($task)) {
            return $this->json(["success" => false, "error" => "Task not found"]);
        }

        return $this->json([
            "success" => true,
            "data" => $task[0] // filterByTask returns an array, we want the first element
        ]);
    }

    
    // UPDATE
    // Required: id parameter in URL
    public function updateAction() {

        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            return $this->json(["success" => false, "error" => "Task ID required"]);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            return $this->json(["success" => false, "error" => "Method not allowed"]);
        }

        // Read PUT data from input ("modified")
        $input = file_get_contents("php://input");
        $data = [];
        parse_str($input, $data);

        // Validate - at least a field has been provided ("modified")
        if(empty($data['title']) && 
           empty($data['description']) && 
           empty($data['status']) && 
          !isset($data['start_at']) && 
          !isset($data['end_at']) && 
          !isset($data['category_id'])) { 
            return $this->json(["success" => false, "error" => "Nothing to update"]);
        }

        // Check if task exists
        $taskModel = new Task();
        $existingTask = $taskModel->filterByTask($id);

        if (empty($existingTask)) {
            return $this->json(["success" => false, "error" => "Task not found"]);
        }

        // Update task
        $result = $taskModel->updateTask($id, $data);
        return $this->json($result);
    }

    // DELETE
    //Required: id parameter in URL

     public function deleteAction(){

        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            return $this->json(["success" => false, "error" => "Task ID required"]);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            return $this->json(["success" => false, "error" => "Method not allowed"]);
        }

        // Check if task exists
        $taskModel = new Task();
        $existingTask = $taskModel->filterByTask($id);

        if (empty($existingTask)) {
            return $this->json(["success" => false, "error" => "Task not found"]);
        }

        // Delete task
        $result = $taskModel->deleteTask($id);
        return $this->json($result);
    }

}

?>
