<?php
class User {

    public static function all() {
        $file = __DIR__ . '/../data/users.json';

        $json = file_get_contents($file);
        $data = json_decode($json, true);

        return $data;
    }

    public static function getAutoIncrementId() {
        $users = self::all();

        if (empty($users)) {
            return 1;
        }

        $lastUser = end($users);
        return intval($lastUser['id']) + 1;
    }

    public static function create($username, $password, $role = "user") {
        $users = self::all();

        $newUser = [
            "id" => self::getAutoIncrementId(),
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role" => $role,
            "created_at" => date("Y-m-d H:i:s")
        ];

        $users[] = $newUser;

        file_put_contents(
            __DIR__ . '/../data/users.json',
            json_encode($users, JSON_PRETTY_PRINT)
        );

        return $newUser;
    }
}
?>
