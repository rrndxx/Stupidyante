<?php

require_once '../config/Database.php';

class Task
{
    private $conn;
    private $usersTable = "users";
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
            $this->conn->beginTransaction();

            $fileStmt = $this->conn->prepare("SELECT file_path FROM {$this->submissionsTable} WHERE task_id = ?");
            $fileStmt->execute([$taskId]);
            $files = $fileStmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($files as $file) {
                $filePath = "../uploads/submissions/" . $file;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->conn->prepare("DELETE FROM {$this->submissionsTable} WHERE task_id = ?")->execute([$taskId]);

            $this->conn->prepare("DELETE FROM {$this->taskAssignmentTable} WHERE task_id = ?")->execute([$taskId]);

            $taskStmt = $this->conn->prepare("DELETE FROM {$this->tasksTable} WHERE id = ?");
            $taskStmt->execute([$taskId]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
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
    public function getAssignedTasks($studentId)
    {
        try {
            $query = "
                SELECT 
                    t.id AS task_id,
                    t.title,
                    t.description,
                    t.due_date,
                    s.status AS submission_status,
                    s.submitted_at
                FROM $this->taskAssignmentTable ta
                JOIN $this->tasksTable t ON ta.task_id = t.id
                LEFT JOIN $this->submissionsTable s 
                    ON s.task_id = t.id AND s.student_id = :sid_sub
                WHERE ta.student_id = :sid_ta
                ORDER BY t.due_date ASC
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':sid_sub', $studentId, PDO::PARAM_INT);
            $stmt->bindParam(':sid_ta', $studentId, PDO::PARAM_INT);
            $stmt->execute();

            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tasks as &$task) {
                if (!empty($task['submission_status'])) {
                    $task['status'] = $task['submission_status'];
                } else {
                    $dueDate = strtotime($task['due_date']);
                    $now = time();
                    $task['status'] = ($dueDate < $now) ? 'late' : 'pending';
                }
                unset($task['submission_status']);
            }

            return $tasks;

        } catch (PDOException $e) {
            return false;
        }
    }
    public function submitTask($taskDetails)
    {
        try {
            $stmt = $this->conn->prepare("SELECT due_date FROM {$this->tasksTable} WHERE id = ?");
            $stmt->execute([$taskDetails['task_id']]);
            $due = strtotime($stmt->fetchColumn() ?: 'now');
            $status = (time() > $due) ? 'late' : 'submitted';

            $filePath = null;
            if (!empty($taskDetails['file']['name'])) {
                $safeName = time() . '_' . basename($taskDetails['file']['name']);
                $dest = "../uploads/submissions/" . $safeName;
                if (move_uploaded_file($taskDetails['file']['tmp_name'], $dest)) {
                    $filePath = $safeName;
                }
            }

            $q = "INSERT INTO {$this->submissionsTable}
                 (task_id, student_id, file_path, submission_url, status)
              VALUES (:task_id, :student_id, :file_path, :submission_url, :status)
              ON DUPLICATE KEY UPDATE
                 file_path = VALUES(file_path),
                 submission_url = VALUES(submission_url),
                 status = VALUES(status),
                 submitted_at = CURRENT_TIMESTAMP";
            $s = $this->conn->prepare($q);
            $s->execute([
                ':task_id' => $taskDetails['task_id'],
                ':student_id' => $taskDetails['student_id'],
                ':file_path' => $filePath,
                ':submission_url' => $taskDetails['submission_url'],
                ':status' => $status
            ]);
            return true;

        } catch (PDOException $e) {
            return false;
        }
    }
    public function viewSubmissions($taskId)
    {
        try {
            $query = "
                SELECT 
                    u.id AS student_id,
                    CONCAT(u.first_name, ' ', u.last_name) AS student_name,
                    s.file_path,
                    s.submitted_at,
                    s.status AS submission_status,
                    s.is_approved,
                    t.due_date
                FROM $this->taskAssignmentTable ta
                JOIN $this->usersTable u ON ta.student_id = u.id
                LEFT JOIN $this->submissionsTable s 
                    ON s.task_id = ta.task_id AND s.student_id = ta.student_id
                JOIN $this->tasksTable t ON t.id = ta.task_id
                WHERE ta.task_id = :task_id
                ORDER BY u.last_name ASC
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
            $stmt->execute();

            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($students as &$student) {
                if (!empty($student['submission_status'])) {
                    $student['status'] = $student['submission_status'];
                } else {
                    $dueDate = strtotime($student['due_date']);
                    $now = time();
                    $student['status'] = ($dueDate < $now) ? 'late' : 'pending';
                }

                unset($student['submission_status']);
            }

            return $students;

        } catch (PDOException $e) {
            return false;
        }
    }
    public function approveSubmission($taskId, $studentId)
    {
        try {
            $query = "UPDATE $this->submissionsTable SET is_approved = 1 WHERE task_id = :task_id AND student_id = :student_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);
            $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>