<?php

require_once __DIR__ . '/../models/Category.php';

// Test Category Update
$categoryModel = new Category();

echo "-------------------\n";
echo "Print Before UPDATE\n";
echo "-------------------\n";

$readCategories = $categoryModel->readAllCategories();
print_r($readCategories);

$updateResult = $categoryModel->updateCategory(2, 
    [
        'description' => 'Adding Description'
    ]
);

$updateResult = $categoryModel->updateCategory(3, 
    [
        'name' => 'Changing Name',
        'description' => 'Changing Description'
    ]
);

echo "-------------------\n";
echo "Print After UPDATE\n";
echo "-------------------\n";

$finalReadCategories = $categoryModel->readAllCategories();
print_r($finalReadCategories);

?>
