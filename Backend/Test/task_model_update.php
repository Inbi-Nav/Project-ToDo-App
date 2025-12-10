<?php

require_once __DIR__ . '/../models/Task.php';



// Test Task Update
$taskModel = new Task();

$readTasks = $taskModel->readAllTasks();
print_r($readTasks);

$updateResult = $taskModel->updateTask( 
    1, 
    [
        'title' => 'Changing TITLE',
        'description' => 'Changing Description',
        'status' => 'in progress'
    ]
);


echo "-------------------\n";
echo "Print After UPDATE\n";
echo "-------------------\n";

$readTasks = $taskModel->readAllTasks();
print_r($readTasks);

?>

