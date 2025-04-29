<?php
require_once __DIR__ . '/Model.php';

class Task extends Model {
    protected $table = 'tasks';

    /**
     * Create a new task
     * 
     * @param array $data Task data
     * @return int|bool The ID of the created task or false on failure
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (title, description, deadline, created_by, status) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['title'],
            $data['description'],
            $data['deadline'],
            $data['created_by'],
            $data['status'] ?? 'pending'
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Get all tasks
     * 
     * @return array All tasks
     */
    public function getAll() {
        $sql = "SELECT t.*, u.first_name, u.last_name, 
                (SELECT COUNT(*) FROM task_assignments WHERE task_id = t.id) as assigned_count
                FROM {$this->table} t
                JOIN users u ON t.created_by = u.id
                ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get tasks by creator
     * 
     * @param int $userId The ID of the creator
     * @return array Tasks created by the user
     */
    public function getByCreator($userId) {
        $sql = "SELECT t.*, 
                (SELECT COUNT(*) FROM task_assignments WHERE task_id = t.id) as assigned_count
                FROM {$this->table} t
                WHERE t.created_by = ?
                ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get tasks assigned to a user
     * 
     * @param int $userId The ID of the user
     * @return array Tasks assigned to the user
     */
    public function getAssignedToUser($userId) {
        $sql = "SELECT t.*, ta.status as assignment_status, u.first_name, u.last_name
                FROM {$this->table} t
                JOIN task_assignments ta ON t.id = ta.task_id
                JOIN users u ON t.created_by = u.id
                WHERE ta.student_id = ?
                ORDER BY t.deadline ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Assign a task to a student
     * 
     * @param int $taskId The ID of the task
     * @param int $studentId The ID of the student
     * @return bool Success status
     */
    public function assignToStudent($taskId, $studentId) {
        $sql = "INSERT INTO task_assignments (task_id, student_id, status) VALUES (?, ?, 'pending')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$taskId, $studentId]);
    }

    /**
     * Get students assigned to a task
     * 
     * @param int $taskId The ID of the task
     * @return array Students assigned to the task
     */
    public function getAssignedStudents($taskId) {
        $sql = "SELECT u.*, ta.status as assignment_status
                FROM users u
                JOIN task_assignments ta ON u.id = ta.student_id
                WHERE ta.task_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$taskId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update task assignment status
     * 
     * @param int $taskId The ID of the task
     * @param int $studentId The ID of the student
     * @param string $status The new status
     * @return bool Success status
     */
    public function updateAssignmentStatus($taskId, $studentId, $status) {
        $sql = "UPDATE task_assignments SET status = ? WHERE task_id = ? AND student_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $taskId, $studentId]);
    }
}
?> 