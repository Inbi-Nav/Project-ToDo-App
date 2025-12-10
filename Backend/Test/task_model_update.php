<?php

require_once __DIR__ . '/../models/Task.php';



// Test Task Update
$taskModel = new Task();


echo "-------------------\n";
echo "Print Before UPDATE\n";
echo "-------------------\n";

$readTasks = $taskModel->readAllTasks();
print_r($readTasks);

$updateResult = $taskModel->updateTask(3, 
    [
        'title' => 'Changing TITLE',
        'description' => 'Changing Description',
        'status' => 'completed'
    ]
);


echo "-------------------\n";
echo "Print After UPDATE\n";
echo "-------------------\n";

$finalReadTasks = $taskModel->readAllTasks();
print_r($finalReadTasks);

?>

