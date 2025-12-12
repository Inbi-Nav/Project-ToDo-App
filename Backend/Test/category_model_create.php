<?php

require_once __DIR__ . '/../models/Category.php';

// Test Category creation
$categoryModel = new Category();

$result = $categoryModel->createCategory('Work', 
    [
    'description' => ' Work Task Plan ',
    'user_id' => 1,
    ]
);


$result_2 = $categoryModel->createCategory('Shopping List', 
    [
    'user_id' => 1,
    ]
);


$result_3 = $categoryModel->createCategory('Personal', 
    [
    'description' => ' My Personal Todo ',
    'user_id' => 1,
    ]
);


$readCategories = $categoryModel->readAllCategories();
print_r($readCategories);


?>