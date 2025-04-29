<?php
require_once __DIR__ . '/Model.php';

class TaskSubmission extends Model {
    protected $table = 'task_submissions';

    public function submit($data) {
        $sql = "INSERT INTO {$this->table} (task_id, student_id, file_path) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['task_id'],
            $data['student_id'],
            $data['file_path']
        ]);
    }

    public function getSubmissionsForTask($taskId) {
        $sql = "SELECT ts.*, u.first_name, u.last_name 
                FROM {$this->table} ts 
                JOIN users u ON ts.student_id = u.id 
                WHERE ts.task_id = ? 
                ORDER BY ts.submission_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$taskId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubmissionByStudentAndTask($studentId, $taskId) {
        $sql = "SELECT * FROM {$this->table} WHERE student_id = ? AND task_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentId, $taskId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateFeedback($submissionId, $feedback, $status) {
        $sql = "UPDATE {$this->table} SET feedback = ?, status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$feedback, $status, $submissionId]);
    }
    
    /**
     * Get all submissions with student and task information
     * 
     * @return array All submissions with related data
     */
    public function getAllSubmissions() {
        $sql = "SELECT ts.*, u.first_name, u.last_name, t.title as task_title 
                FROM {$this->table} ts 
                JOIN users u ON ts.student_id = u.id 
                JOIN tasks t ON ts.task_id = t.id 
                ORDER BY ts.submission_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all submissions by a specific student
     * 
     * @param int $studentId The ID of the student
     * @return array All submissions by the student
     */
    public function getSubmissionsByStudent($studentId) {
        $sql = "SELECT ts.*, t.title as task_title 
                FROM {$this->table} ts 
                JOIN tasks t ON ts.task_id = t.id 
                WHERE ts.student_id = ? 
                ORDER BY ts.submission_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 