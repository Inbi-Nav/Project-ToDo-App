<?php

require_once __DIR__ . '/../models/Category.php';

// Test Category Update
$categoryModel = new Category();

echo "-------------------\n";
echo "Print Before UPDATE\n";
echo "-------------------\n";

$readCategories = $categoryModel->readAllCategories();
print_r($readCategories);

$deleteResult = $categoryModel->deleteCategory(3);

echo "-------------------\n";
echo "Print After UPDATE\n";
echo "-------------------\n";

$finalReadCategories = $categoryModel->readAllCategories();
print_r($finalReadCategories);

?>