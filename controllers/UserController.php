<?php

require_once __DIR__ . '/../models/User.php';


class UserController extends ApplicationController
{

    public function indexAction() {
        $userModel = new User();
        $users = $userModel->all();

        $this->json(["success" => true,"data" => $users ]);

        exit();
    }

    public function showAction() {
        $id = $_GET["id"] ?? null;

       if (!$id) {
        return $this->json([ "success"=> false, "error"=> "No ID found"]);
       } 

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            return $this->json([ "success"=> false, "error" => "User not found :(" ]);
        }
        return $this->json(["success" => true, "data" => $user]);
    }

    public function createAction() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            return $this->json(["success" => false, "error" => "Method not allowed"]);
        }

        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;


        if (empty($username)) {
            return $this->json(["success" => false,"error" => "username id required"]);
        }

        if (empty($password)) {
            return $this->json(["success" => false, "error" => "password is required"
            ]);
        }

        $userModel = new User();
        $newUser = $userModel->createUser($username, $password);

        return $this->json(["success" => true, "data" => $newUser]);
    }


    public function updateAction() {

        $id = $_GET['id'] ?? null;

        if (!$id) {
            return $this->json([ "success" => false, "error" => "ID required"]);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            return $this->json([ "success" => false, "error" => "Method not allowed"]);
        }

        // read updated data 
        $input = file_get_contents("php://input");
        $data = [];
        parse_str($input, $data);

        // validate
        if (empty($data['username']) && empty($data['password']) && empty($data['image'])) {
            return $this->json([ "success" => false, "error" => "Nothing to update"]);
        }

        //check if user exists
        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            return $this->json([ "success" => false, "error" => "User not found :("
            ]);
        }

        // Update user
        $updatedUser = $userModel->updateUser($id, $data);

        if (!$updatedUser) {
            return $this->json([ "success" => false, "error" => "Update failed"
            ]);
        }

        // return success
        return $this->json([
            "success" => true,
            "data" => $updatedUser
        ]);
    }


    public function deleteAction() {

         $id = $_GET['id'] ?? null;

        if (!$id) {
            return $this->json([ "success" => false, "error" => "ID required"]);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            return $this->json(["success" => false, "error" => "Method not allowed"]);
        }

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            return $this->json(["success" => false, "error" => "User not found"]);
        }

        $deletedUser = $userModel->deleteUser($id);

          if (!$deletedUser) {
            return $this->json(["success" => false,"error" => "Delete failed"]);
    }

        return $this->json([
            "success" => true, "message" => "User deleted correctly"]);
    }

    //register

    public function registerAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('user/register');
            return;
        }

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        $errors = [];

        if (empty($username)) {
            $errors[] = 'Username is required';
        }

        if (empty($password)) {
            $errors[] = 'Password is required';
        }

        $userModel = new User();
        $existingUser = $userModel->findByUsername($username);

        if ($existingUser) {
            $errors[] = 'User already exists';
        }

        if (!empty($errors)) {
            $this->render('user/register', ["errors" => $errors]);
            return;
        }

        $userModel->createUser($username, $password);

        header("Location: /login");
        exit();
    }
    
    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('user/login');
            return;
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        $errors = [];

        if (empty($username)) {
            $errors[] = "Username is required";
        }

        if (empty($password)) {
            $errors[] = "Password is required";
        }

        if (!empty($errors)) {
            $this->render('user/login', ["errors" => $errors]);
            return;
        }

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user) {
            $errors[] = "User does not exist";
            $this->render('user/login', ["errors" => $errors]);
            return;
        }

        if (!password_verify($password, $user['password'])) {
            $errors[] = "Incorrect password";
            $this->render('user/login', ["errors" => $errors]);
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        header("Location: /dashboard");
        exit();
    }

    //logout
    public function logoutAction()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: /login");
        exit();
    }

    public function dashboardAction() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION["user_id"])) {
            header("Location: /login");
            exit();
        }

        $categoryModel = new Category();
        $taskModel= new Task();

        $categories = $categoryModel->filterByUserId($_SESSION["user_id"]);
        $tasks= $taskModel->filterByUserId($_SESSION["user_id"]);

        $this->render("user/dashboard", ["categories" => $categories, "tasks" => $tasks, "username" => $_SESSION["username"]]);
        
    }
}
