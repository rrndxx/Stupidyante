<?php

require_once '../models/TaskModel.php';

class TaskController
{
    public function processAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = isset($_POST['action']) ? $_POST['action'] : '';

            switch ($action) {
                case 'addtask':
                    $this->addTask();
                    break;

                case 'edittask':
                    $this->editTask();
                    break;

                case 'deletetask':
                    $this->deleteTask();
                    break;

                case 'submittask':
                    $this->submitTask();
                    break;

                case 'approvesubmission':
                    $this->approveTask();
                    break;

                default:
                    echo "Invalid action.";
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $action = isset($_GET['action']) ? $_GET['action'] : '';

            switch ($action) {
                case 'get_all_tasks':
                    $this->getAllTasks();
                    break;

                case 'get_task_by_id':
                    $this->getTaskById();
                    break;

                case 'get_assigned_task':
                    $this->getAssignedTasks();
                    break;

                case 'view_submissions':
                    $this->viewSubmissions();
                    break;

                default:
                    echo "Invalid action.";
            }
        }
    }

    public function addTask()
    {
        $taskData = [
            'task_title' => $_POST['task_title'],
            'task_description' => $_POST['task_description'],
            'due_date' => $_POST['due_date'],
            'students' => $_POST['students'] ?? [],
        ];

        $task = new Task();
        $success = $task->addTask($taskData);

        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? "Task added successfully!" : "Failed adding task."
        ]);
    }
    public function editTask()
    {
        $taskData = [
            'task_id' => $_POST['task_id'],
            'task_title' => $_POST['task_title'],
            'task_description' => $_POST['task_description'],
            'due_date' => $_POST['due_date'],
            'students' => $_POST['students'] ?? [],
        ];

        $task = new Task();
        $success = $task->editTask($taskData);

        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? "Task updated successfully!" : "Failed updating task."
        ]);

    }
    public function deleteTask()
    {
        $task = new Task();
        $success = $task->deleteTask($_POST['task_id']);

        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? "Task deleted successfully!" : "Failed deleting task."
        ]);
    }
    public function getAllTasks()
    {
        $task = new Task();
        $tasks = $task->getAllTasks();

        if ($tasks !== false) {
            echo json_encode($tasks);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch tasks.']);
        }
    }
    public function getTaskById()
    {
        $task = new Task();
        $result = $task->getTaskById($_GET['task_id'] ?? null);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch specific task.']);
        }
    }
    public function getAssignedTasks()
    {
        session_start();

        $task = new Task();
        $assignedTask = $task->getAssignedTasks($_SESSION['user_id']);

        if ($assignedTask === false) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error fetching assigned tasks.'
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'data' => $assignedTask
            ]);
        }
    }
    public function submitTask()
    {
        session_start();

        $submissionData = [
            'task_id' => $_POST['task_id'],
            'student_id' => $_SESSION['user_id'],
            'file' => $_FILES['file'] ?? null,
            'submission_url' => $_POST['submission_url'] ?? null
        ];

        $task = new Task();
        $submitTask = $task->submitTask($submissionData);

        echo json_encode([
            'status' => $submitTask ? 'success' : 'error',
            'message' => $submitTask ? 'Submitted!' : 'Could not submit task.'
        ]);
    }
    public function viewSubmissions()
    {
        $task = new Task();
        $submissions = $task->viewSubmissions($_GET['task_id']);

        if ($submissions !== false) {
            echo json_encode(['status' => 'success', 'data' => $submissions]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch submissions']);
        }
    }
    public function approveTask()
    {
        $task = new Task();
        $approved = $task->approveSubmission($_POST['task_id'], $_POST['student_id']);

        echo json_encode([
            'status' => $approved ? 'success' : 'error',
            'message' => $approved ? 'Submission Approved' : 'Error approving submission.',
        ]);
    }
}

$taskController = new TaskController();
$taskController->processAction();

?>