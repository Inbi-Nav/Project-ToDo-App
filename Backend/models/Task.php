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

    private function readTasks(){
        $jsonContent = file_get_contents($this->filePath);
        $tasks = json_decode($jsonContent,true);
        
        //return empty if file is invalid or empty
        return is_array($tasks) ? $tasks :[];
    }


    // Write tasks (generic)

    private function writeTasks ($tasks){
        $jsonContent = json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); // makes JSON more readable
        return file_put_contents($this->filePath, $jsonContent)!== false;
    }


    // Get task ID - generate manual auto_increment task ID (generic)

    private function getNextId(){
        $tasks=$this->readTasks();

        if (empty($tasks)){
            return 1;
        }

        $maxId=max(array_column($tasks,'id'));
        return $maxId + 1;
    }

    // Create tasks 
    // QQ: Shall we consider include a required fields  valdation to our create functions? 

    public function createTask($item){
         $tasks = $this->readTasks();
         $newId = $this->getNextId();

         $newTask = [
            'id' => $newId,
            'title' => trim($item['title']),
            'description' => isset($data['description']) ? trim($item['description']) : '',
            'status' => 'pending',      // Default status during creation (hidden in the view). Later with updates, Enum vs Conditional. 
            'created_at' => date('Y-m-d H:i:s'),
            'start_at' => null,         // Should be set when task starts (status "in progress") or manually set by the user.
            'end_at' => null,           // Should be set when task completes (status "completed"), or afterwards manually updated by the user.
            'user_id' => (int)$item['user_id'],
            'category_id' => isset($item['category_id']) ? (int)$item['category_id'] : null
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


    // Update (modify) tasks
    

    // Delete tasks


    // Read/Get All tasks


    // Filter by Specific Tasks


}


?>