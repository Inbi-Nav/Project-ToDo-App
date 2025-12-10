<?php

// declare(strict_types=1);

class Category {

    private int $id;
    private string $name;
    private ?string $description = null;
    private string $created_at;

    private string $filePath;


    public function __construct() {
        $this->filePath = __DIR__ . '/../data/categories.json';
    
        // Create the file if it doesn't exist
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }


     // Read category(ies) (generic)
    private function readCategories(): array {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $jsonContent = file_get_contents($this->filePath);
        $categories = json_decode($jsonContent, true);
        
        return is_array($categories) ? $categories : [];
    }

    // Write category (generic)

    private function writeCategories(array $categories): bool {
        $jsonContent = json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return file_put_contents($this->filePath, $jsonContent) !== false;
    }

    // Get category ID - generate manual auto_increment ID (generic)

    private function getNextId(): int {
        $categories = $this->readCategories();

        if (empty($categories)) {
            return 1;
        }

        // Find the maximum ID and add 1
        $maxId = max(array_column($categories, 'id'));
        return $maxId + 1;
    }

    // Create Category 


    // Read Category 


    // Update Category 


    // Delete Category 

}
?>