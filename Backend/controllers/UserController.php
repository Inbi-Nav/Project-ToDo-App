<?php

require_once __DIR__ . '/../models/User.php';

class UserController extends ApplicationController
{

    public function indexAction() {
        $userModel = new User();
        $users = $userModel->all();

        $this->render('user/inde', ["title" => "User List",
        "users"=> $users
    ]);
    }
}
