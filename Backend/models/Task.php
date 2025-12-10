<?php

// declare(strict_types=1);

Class Task {

    private int $id;
    private string $title;
    private string $description;
    private string $status;
    private string $created_at;
    private ?string $start_at = null;
    private ?string $end_at = null;
    private int $user_id;
    private ?int $category_id = null;

    private $filePath;

    public function __construct() {
        $this->filePath = __DIR__ . '/../data/tasks.json';
    
        // Create the file if it doesn't exist
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    // Read tasks (generic)

    private function readTasks(): array {
        $jsonContent = file_get_contents($this->filePath);
        $tasks = json_decode($jsonContent,true);
        
        //return empty if file is invalid or empty
        return is_array($tasks) ? $tasks :[];
    }


    // Write tasks (generic)

    private function writeTasks (array $tasks): bool{
        $jsonContent = json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); // makes JSON more readable
        return file_put_contents($this->filePath, $jsonContent)!== false;
    }


    // Get task ID - generate manual auto_increment task ID (generic)

    private function getNextId(): int {
        $tasks=$this->readTasks();

        if (empty($tasks)){
            return 1;
        }

        $maxId=max(array_column($tasks,'id'));
        return $maxId + 1;
    }
    

    // Create task 

    public function createTask(string $title, array $data): array {
         $tasks = $this->readTasks();
         $newId = $this->getNextId();

         $newTask = [
            'id' => $newId,
            'title' => trim($title),
            'description' => isset($data['description']) ? trim($data['description']) : '',
            'status' => 'pending',      // Default status
            'created_at' => date('Y-m-d H:i:s'),
            'start_at' => null,         
            'end_at' => null,           
            'user_id' => (int)$data['user_id'],
            'category_id' => isset($data['category_id']) ? (int)$data['category_id'] : null
        ];

        $tasks[] = $newTask;

        // Save to file
        if ($this->writeTasks($tasks)) {
            return [
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $newTask
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error saving task to file'
            ];
        }

    }

    // Read All tasks
    public function readAllTasks() {
        return $this->readTasks();
    }


    // Update (modify) tasks
    

    // Delete tasks





    // Filter by Specific Tasks


}


?>