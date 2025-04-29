<?php
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';

class TaskController {
    private $taskModel;
    private $userModel;

    public function __construct() {
        $this->taskModel = new Task();
        $this->userModel = new User();
    }

    /**
     * Create a new task
     * 
     * @param array $data Task data
     * @param array $studentIds Array of student IDs to assign the task to
     * @return bool Success status
     */
    public function createTask($data, $studentIds) {
        // Create the task
        $taskId = $this->taskModel->create($data);
        
        if (!$taskId) {
            return false;
        }
        
        // Assign the task to students
        foreach ($studentIds as $studentId) {
            $this->taskModel->assignToStudent($taskId, $studentId);
        }
        
        return true;
    }

    /**
     * Get all tasks
     * 
     * @return array All tasks
     */
    public function getAllTasks() {
        return $this->taskModel->getAll();
    }

    /**
     * Get tasks created by a specific user
     * 
     * @param int $userId The ID of the user
     * @return array Tasks created by the user
     */
    public function getTasksCreatedByUser($userId) {
        return $this->taskModel->getByCreator($userId);
    }

    /**
     * Get tasks assigned to a specific user
     * 
     * @param int $userId The ID of the user
     * @return array Tasks assigned to the user
     */
    public function getTasksForUser($userId) {
        return $this->taskModel->getAssignedToUser($userId);
    }

    /**
     * Get a task by ID
     * 
     * @param int $taskId The ID of the task
     * @return array Task data
     */
    public function getTaskById($taskId) {
        return $this->taskModel->findById($taskId);
    }

    /**
     * Get students assigned to a task
     * 
     * @param int $taskId The ID of the task
     * @return array Students assigned to the task
     */
    public function getAssignedStudents($taskId) {
        return $this->taskModel->getAssignedStudents($taskId);
    }

    /**
     * Update a task
     * 
     * @param int $taskId The ID of the task
     * @param array $data Updated task data
     * @return bool Success status
     */
    public function updateTask($taskId, $data) {
        return $this->taskModel->update($taskId, $data);
    }

    /**
     * Delete a task
     * 
     * @param int $taskId The ID of the task
     * @return bool Success status
     */
    public function deleteTask($taskId) {
        return $this->taskModel->delete($taskId);
    }

    /**
     * Get all students for task assignment
     * 
     * @return array All students
     */
    public function getAllStudents() {
        return $this->userModel->getAllStudents();
    }
}
?> 