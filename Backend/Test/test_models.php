<?php

require_once __DIR__ . '/../models/Task.php';

// Test Task creation
$taskModel = new Task();
$result = $taskModel->createTask(
    ' 001 - NEW Test Task Model of ToDo App ',
    [
    'title' => ' NEW Test Task Model of ToDo App ',
    'description' => ' Complete Sprint ',
    'user_id' => 1,
    'category_id' => 1
    ]
);



$result_2 = $taskModel->createTask(
    ' 002 - NEW Test Task Model of ToDo App ', 
    [
    'description' => ' Complete Sprint ',
    'user_id' => 2,
    'category_id' => 2
    ]
);


$result_3 = $taskModel->createTask(
    ' 003 - NEW Test Task Model of ToDo App ', 
    [
    'description' => ' Complete Sprint ',
    'user_id' => 3,
    'category_id' => 3
    ]
);


$readTasks = $taskModel->readAllTasks();
print_r($readTasks);


?>

