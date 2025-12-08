<?php

class User {

    private $id;
    private $username;
    private $password;
    private $created_at;

    private $filePath;

    public function __construct() {
        $this->filePath = __DIR__ . '/../data/users.json';
    }

// Load all users
    public function all() {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        return json_decode($json, true);
    }

// return user that have same id  
    public function findById($id) {
        $users = $this->all();

        foreach($users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }

// create a new user 
    public function createUser($username, $password) {
        $users = $this->all();
        
        $newId = $this->getNextId();

        $newUser = [
            "id" => $newId,
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "created_at" => date("Y-m-d H:i:s")
        ];

        $users[] = $newUser;

        $this->saveAll($users);

        return $newUser;
    }

    private function getNextId() {
        $users = $this->all();

        if (empty($users)) {
            return 1;
        }

        $lastUser = end($users);
        $lastId = (int)$lastUser['id'];

        return $lastId + 1;
    }
// update User 
    public function updateUser($id, $data) {
        $users = $this->all();

        foreach ($users as $key => $user) {
            if ($user['id'] == $id) {

                if(isset($data['username'])) {
                    $users[$key]['username'] = $data['username'];
                }

                if(isset($data['password'])) {
                    $users[$key]['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
                $this->saveAll($users);
                return $users[$key];
            }
        }
        return null;
    }

    public function deleteUser($id) {
        $users = $this->all();

        foreach ($users as $key => $user) {
            if ($user['id'] == $id) {

                unset($users[$key]);

                $users = array_values($users);

                $this->saveAll($users);

                return true;
            }
        }
        return false;
    }


     public function saveAll($users) {
    $json = json_encode($users, JSON_PRETTY_PRINT);
    file_put_contents($this->filePath, $json);
    }

    // Find a user by username to validate the 
    public function findByUsername($username) {
        $users = $this->all();

        foreach ($users as $user) {
            if ($user['username'] === $username) {
                return $user; 
            }
        }

        return null; 
    }

}
