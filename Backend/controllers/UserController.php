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
            $errors[] ='El nomnre de usuario es requerido';
        }
    
        if (empty($password)) {
            $errors[] ='La contraseña de usuario es requerido';
        }
        // Check existing users
        $userModel = new User();
        $existingUser = $userModel->findByUsername($username);

        if($existingUser) {
            $errors[] = 'El usuario ya existe';
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
    
}


// logout
    public function logoutAction($id)
    {
    }
}
