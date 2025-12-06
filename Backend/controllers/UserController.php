<?php

require_once __DIR__ . '/../models/User.php';

class UserController extends ApplicationController
{

// register
    public function registerAction()
    {
        // GET request → show register form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render('users/register');
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
            $this->render('users/register', ["errors" => $errors]);
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

    // sttart the session
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

    // unset the session 

    // destry session

    // redirect to /login 
    
}
