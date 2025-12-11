<?php

require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';

require_once __DIR__ . '/../models/Category.php';

class TaskController extends ApplicationController{ 
   
    // CREATE
    // Required: title, user_id

      public function indexAction() {
        $userModel = new Task();
        $tasks = $userModel->readAllTasks();

        $this->json(["success" => true,"data" => $tasks ]);

        exit();
    }


    public function createAction() {

        if($_SERVER['REQUEST_METHOD'] !=='POST') {
            return $this->json(["success" => false, "error" => "Method not allowed"]);
        }

        $title = isset($_POST['title']) ? trim($_POST['title']) : null;
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
        $start_at = isset($_POST['start_at']) ? $_POST['start_at'] : null;
        $end_at = isset($_POST['end_at']) ? $_POST['end_at'] : null;

        if (empty($title)) {
            return $this->json(["success" => false, "error" => "Title is required"]);
        }

        if (empty($user_id)) {
            return $this->json(["success" => false, "error" => "User_id is required"]);
        }

        // Create $data array
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

        return $this->json(["sucsess" => true,"data" =>$result]);
    }


    // READ (indexAction & showAction)


    // UPDATE


    // DELETE



}

?>
