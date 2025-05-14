<?php

require_once '../config/Database.php';

class Task
{
    private $conn;
    private $tasksTable = "tasks";
    private $taskAssignmentTable = "task_assignments";
    private $submissionsTable = "submissions";

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function addTask($taskDetails)
    {
        try {
            $this->conn->beginTransaction();

            session_start();
            $createdBy = $_SESSION['user_id'];

            $stmt = $this->conn->prepare("INSERT INTO tasks (title, description, due_date, created_by) VALUES (:title, :description, :due_date, :created_by)");
            $stmt->bindParam(':title', $taskDetails['task_title']);
            $stmt->bindParam(':description', $taskDetails['task_description']);
            $stmt->bindParam(':due_date', $taskDetails['due_date']);
            $stmt->bindParam(':created_by', $createdBy);
            $stmt->execute();

            $taskId = $this->conn->lastInsertId();

            if (!empty($taskDetails['students']) && is_array($taskDetails['students'])) {
                $stmtAssign = $this->conn->prepare("INSERT INTO $this->taskAssignmentTable (task_id, student_id) VALUES (:task_id, :student_id)");

                foreach ($taskDetails['students'] as $studentId) {
                    $stmtAssign->bindParam(':task_id', $taskId);
                    $stmtAssign->bindParam(':student_id', $studentId);
                    $stmtAssign->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Add Task Error: " . $e->getMessage());
            return false;
        }
    }

    public function editTask($taskDetails)
    {
        try {
            $this->conn->beginTransaction();

            $taskId = $taskDetails['task_id'];
            $title = $taskDetails['task_title'];
            $description = $taskDetails['task_description'];
            $dueDate = $taskDetails['due_date'];
            $students = $taskDetails['students'];

            $query = "UPDATE $this->tasksTable SET title = ?, description = ?, due_date = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$title, $description, $dueDate, $taskId]);

            $this->conn->prepare("DELETE FROM task_assignments WHERE task_id = ?")->execute([$taskId]);

            $assignStmt = $this->conn->prepare("INSERT INTO task_assignments (task_id, student_id) VALUES (?, ?)");
            foreach ($students as $studentId) {
                $assignStmt->execute([$taskId, $studentId]);
            }

            $this->conn->commit();

            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function deleteTask($taskId)
    {
        try {
            $query = "DELETE FROM $this->tasksTable WHERE id = ?";
            $stmnt = $this->conn->prepare($query);

            return $stmnt->execute([$taskId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllTasks()
    {
        try {
            $query = "SELECT * FROM $this->tasksTable";
            $stmnt = $this->conn->prepare($query);
            $stmnt->execute();
            return $stmnt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getTaskById($taskId)
    {
        if (!$taskId || !is_numeric($taskId)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid task ID.']);
            return;
        }

        $taskId = (int) $taskId;

        try {
            $stmt = $this->conn->prepare("SELECT * FROM $this->tasksTable WHERE id = ?");
            $stmt->execute([$taskId]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            $assignedStmt = $this->conn->prepare("SELECT student_id FROM $this->taskAssignmentTable WHERE task_id = ?");
            $assignedStmt->execute([$taskId]);
            $studentIds = $assignedStmt->fetchAll(PDO::FETCH_COLUMN);

            $task['students'] = $studentIds;

            return $task;
        } catch (PDOException $e) {
            return false;
        }
    }

}
?>