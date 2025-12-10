<?php

// declare(strict_types=1);

class Category {

    private int $id;
    private string $name;
    private ?string $description = null;
    private string $created_at;
    private int $user_id;

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
    
        public function createCategory(string $name, array $data): array {
        $categories = $this->readCategories();
        $newId = $this->getNextId();

        $newCategory = [
            'id' => $newId,
            'name' => trim($name),
            'description' => isset($data['description']) ? trim($data['description']) : null,
            'created_at' => date('Y-m-d H:i:s'),
            'user_id' => (int)$data['user_id']
        ];

        $categories[] = $newCategory;

        // Save to file
        $this->writeCategories($categories);
        return $newCategory;
    }


    // Read Category
    public function readAllCategories() {
        return $this->readCategories();
    }

    public function findByCategoryId(int $id): ?array {
        $categories = $this->readCategories();

        foreach ($categories as $category) {
            if ($category['id'] == $id) {
                return $category;
            }
        }
        return null;
    }

    // Update Category 


    // Delete Category 

}
?>