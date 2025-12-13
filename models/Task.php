<?php

// declare(strict_types=1);

Class Task {

    private int $id;
    private string $title;
    private string $description;
    private string $status;
    private string $created_at;
    private ?string $start_at;
    private ?string $end_at;
    private int $user_id;
    private ?int $category_id;

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
    
    // To validate status value
    private function isValidStatus(string $status): bool {
        $validStatuses = ['pending', 'in progress', 'completed'];
        return in_array(strtolower($status), $validStatuses);
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
            'start_at' => isset($data['start_at']) ? $data['start_at'] : null,        
            'end_at' => isset($data['end_at']) ? $data['end_at'] : null,            
            'user_id' => (int)$data['user_id'],
            'category_id' => isset($data['category_id']) ? (int)$data['category_id'] : null
        ];

        $tasks[] = $newTask;

        // Save to file
        // $this->writeTasks($tasks);
        // return $newTask;
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

    // Read tasks (All)
    public function readAllTasks() {
        return $this->readTasks();
    }

    // Update (modify) tasks
    public function updateTask(int $id, array $data): array {
        $tasks = $this->readTasks();
        $taskFound = false;

        foreach ($tasks as $key => $task) {
            if ($task['id'] == $id) {
                $taskFound = true;

                if (isset($data['title'])) {
                    $tasks[$key]['title'] = trim($data['title']);
                }

                if (isset($data['description'])) {
                    $tasks[$key]['description'] = trim($data['description']);
                }

                if (isset($data['status'])) {
                    $status = strtolower(trim($data['status']));
                    
                    if (!$this->isValidStatus($status)) {
                        return [
                            'success' => false,
                            'message' => 'Invalid status. Must be: pending, in progress, or completed'
                        ];
                    }

                    $tasks[$key]['status'] = $status;
                }

                // Allow manual timestamp updates
                if (isset($data['start_at'])) {
                    $tasks[$key]['start_at'] = $data['start_at'];
                }

                if (isset($data['end_at'])) {
                    $tasks[$key]['end_at'] = $data['end_at'];
                }

                if (isset($data['category_id'])) {
                    $tasks[$key]['category_id'] = $data['category_id'] !== null ? (int)$data['category_id'] : null;
                }

                // Save changes
                // $this->writeTasks($tasks);
                // return $tasks[$key];
                if ($this->writeTasks($tasks)) {
                    return [
                        'success' => true,
                        'message' => 'Task updated successfully',
                        'task' => $tasks[$key]
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Error saving changes to file'
                    ];
                }
            }
        }
 
        return [
            'success' => false,
            'message' => 'Task not found'
        ];
    }

    // Delete tasks
    public function deleteTask(int $id): array{
        $tasks = $this->readTasks();

        foreach($tasks as $key=>$task) {
            if($task['id'] == $id) {
                
                //Remove
                unset($tasks[$key]);
                
                //Reindex
                $tasks = array_values($tasks);

                //Save changes
                 if ($this->writeTasks($tasks)) {
                    return [
                        'success' => true,
                        'message' => 'Task deleted successfully'
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Error saving changes to file'
                    ];
                }
            }
        }

        return [
            'success' => false,
            'message' => 'Task not found'
        ];
    }

    // Filter by Specific Paramenter
    public function filterByTask(int $id): array {
        $tasks = $this->readTasks();
        $result=[];

        foreach($tasks as $task) {
            if($task['id'] === $id){
                $result[] = $task;
            }
        }
        return $result;
    }

        public function filterByUserId(int $userId): array {
        $tasks = $this->readTasks();
        $result=[];

        foreach($tasks as $task) {
            if($task['user_id'] == $userId){
                $result[] = $task;
            }
        }
        return $result;
    }

    public function filterByStatus(string $status): array {
        $tasks = $this->readTasks();
        $result = [];

        foreach ($tasks as $task) {
            if ($task['status'] === $status) {
                $result[] = $task;
            }
        }
        return $result;
    }

    public function filterByCategoryId(int $categoryId): array {
        $tasks = $this->readTasks();
        $result = [];

        foreach($tasks as $task) {
            if($task['category_id'] === $categoryId){
                $result[] = $task;
            }
        }
        return $result;
    }

}

?>