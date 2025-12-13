<?php

require_once __DIR__ . '/../models/Task.php';

// Test Task Filters
$taskModel = new Task();

echo "-------------------\n";
echo "Filter Task ID \n";
echo "-------------------\n";

$filterTaskByID = $taskModel->filterByTask(2);
print_r($filterTaskByID);


echo "------------------------\n";
echo "Filter Tasks by User ID \n";
echo "------------------------\n";

$filterByUserId = $taskModel->filterByUserId(1);
print_r($filterByUserId);


echo "-------------------\n";
echo "Filter by Status \n";
echo "-------------------\n";

$inProgressTasks = $taskModel->filterByStatus("completed");
print_r($inProgressTasks);


echo "-------------------\n";
echo "Filter by Category \n";
echo "-------------------\n";

$filterTasksByCategory = $taskModel->filterByCategoryId(3);
print_r($filterTasksByCategory);


?>