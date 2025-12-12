<?php

require_once __DIR__ . '/../models/Task.php';

// Test Task Update
$taskModel = new Task();

echo "-------------------\n";
echo "Print Before DELETE\n";
echo "-------------------\n";
$readTasks = $taskModel->readAllTasks();
print_r($readTasks);


$deleteResult = $taskModel->deleteTask(2);


echo "-------------------\n";
echo "Print After DELETE \n";
echo "-------------------\n";

$finalReadTasks = $taskModel->readAllTasks();
print_r($finalReadTasks);

?>