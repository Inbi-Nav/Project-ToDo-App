<?php

require_once __DIR__ . '/../models/User.php';

class UserController extends ApplicationController
{

// register
    public function registerAction()
    {
        // GET request which shows register form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('user/register');
            return;
        }
        // POST -> read data
        $username = $_POST['username'];
        $password = $_POST['password'];

        $errors = [];
        if (empty($username)) {
            $errors[] ='Username is required';
        }
    
        if (empty($password)) {
            $errors[] ='Password is required';
        }
        // Check existing users
        $userModel = new User();
        $existingUser = $userModel->findByUsername($username);

        if($existingUser) {
            $errors[] = 'User already exists';
        }
        // if the errors exist show the register view again
        if(!empty($errors)) {
            $this->render('user/register', ["errors" => $errors]);
            return;
        }
        // create a new user
        $userModel->create($username, $password);

        // redirect to login
        header("Location: /login");
        exit();

    }

// Login
    public function loginAction()
{
    // 1. GET request → show login form
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $this->render('user/login');
        return;
    }

      // POST -> read data
    $username = $_POST['username'];
    $password = $_POST['password'];

    $errors = [];

    // 3. Validate input
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

    // 4. Check if user exists
    $userModel = new User();
    $user = $userModel->findByUsername($username);

    if (!$user) {
        $errors[] = "User not exist";
        $this->render('user/login', ["errors" => $errors]);
        return;
    }

    // verify the password
    if (!password_verify($password, $user['password'])) {
        $errors[] = "Incorrect ";
        $this->render('user/login', ["errors" => $errors]);
        return;
    }

    // start the session
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["role"] = $user["role"];

    // rRedirect user back to login
    header("Location: /tasks");
    exit();
}


// logout
    public function logoutAction()
    {

    // start session
     if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }


    // unset the session 
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

    // destry session
    session_destroy();

    // redirect to /login 
    header("Location: /tasks");
    exit();
    }
}
