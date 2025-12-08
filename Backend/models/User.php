<?php

class User {

    private $id;
    private $username;
    private $password;
    private $role;
    private $created_at;

    private $filePath;

    public function __construct() {
        $this->filePath = __DIR__ . '../data/users.json';
    }

// Load all users
    public function all() {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        return json_decode($json, true);
    }

// Find a user by username to validate 
    public function findByUsername($username) {
        $users = $this->all();

        foreach ($users as $user) {
            if ($user['username'] === $username) {
                return $user; 
            }
        }

        return null; 
    }

// create a new user 
    public function create($username, $password, $role = "user") {
        $users = $this->all();
        
        $newId = $this->getNextId();

        $newUser = [
            "id" => $newId,
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role" => $role,
            "created_at" => date("Y-m-d H:i:s")
        ];

        $users[] = $newUser;

        $this->saveAll($users);

        return $newUser;
    }
// Save all users in the JSON
    public function saveAll($users) {
        $json = json_encode($users, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $json);
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
}
