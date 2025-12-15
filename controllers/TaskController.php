<?php

require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';

class TaskController extends ApplicationController{ 

    public function createAction() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Method not allowed";
            exit();
        }

        $title = trim($_POST['title'] ?? '');
        $user_id = $_POST['user_id'] ?? null;
        $description = trim($_POST['description'] ?? '');
        $category_id = $_POST['category_id'] ?? null;

        if ($title === '') {
            echo "Title is required";
            exit();
        }

        if (!$user_id) {
            echo "User ID is required";
            exit();
        }

        if (!empty($category_id)) {
            $categoryModel = new Category();
            $category = $categoryModel->findByCategoryId($category_id);

            if (!$category) {
                echo "Category not found";
                exit();
            }
        }

        $data = [
            'user_id' => $user_id,
            'description' => $description
        ];

        if (!empty($category_id)) {
            $data['category_id'] = $category_id;
        }

        $taskModel = new Task();
        $taskModel->createTask($title, $data);

        header("Location: /dashboard?cat=" . $category_id);
        exit();
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

    if (!$id) { echo "Task ID required"; exit(); }

    $taskModel = new Task();
    $existingTask = $taskModel->filterByTask($id);

    if (empty($existingTask)) { echo "Task not found"; exit(); }

    // -------------------------------
    // STEP 1: SHOW EDIT FORM
    // -------------------------------
    if (isset($_POST['edit_mode'])) {
        // Render edit form inside dashboard
        include VIEW_PATH . "/tasks/edit-inline.phtml";
        return;
    }

    // -------------------------------
    // STEP 2: HANDLE REAL UPDATE
    // -------------------------------
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        echo "Method not allowed"; exit();
    }

    // Read PUT data
    $input = file_get_contents("php://input");
    $data = [];
    parse_str($input, $data);

    // Update task
    $taskModel->updateTask($id, $data);

    // Redirect back
    $category_id = $existingTask[0]['category_id'] ?? '';
    header("Location: /dashboard?cat=" . $category_id);
    exit();
}

    // DELETE
    //Required: id parameter in URL

    public function deleteAction() {

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    if (!$id) {
        echo "Task ID required";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        echo "Method not allowed";
        exit();
    }

    $taskModel = new Task();
    $existingTask = $taskModel->filterByTask($id);

    if (empty($existingTask)) {
        echo "Task not found";
        exit();
    }

    $category_id = $existingTask[0]['category_id'] ?? '';

    // DELETE
    $taskModel->deleteTask($id);

    // REDIRECT
    header("Location: /dashboard?cat=" . $category_id);
    exit();
}

}

?>
