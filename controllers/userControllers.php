<?php

require_once '../models/UserModel.php';

class UserController
{
    public function processAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = isset($_POST['action']) ? $_POST['action'] : '';

            switch ($action) {
                case 'register':
                    $this->registerUser();
                    break;

                case 'login':
                    $this->loginUser();
                    break;

                default:
                    echo "Invalid action.";
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $action = isset($_GET['action']) ? $_GET['action'] : '';

            switch ($action) {
                case 'get_all_students':
                    $this->getAllStudents();
                    break;

                case 'get_student_count':
                    $this->getStudentCount();
                    break;

                default:
                    echo "Invalid action.";
            }
        }
    }

    public function registerUser()
    {
        $userData = [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'gender' => $_POST['gender'],
            'phone_number' => $_POST['phone_number'],
            'course' => $_POST['course'],
            'address' => $_POST['address'],
            'birthdate' => $_POST['birthdate'],
            'role' => 'student'
        ];

        $profilePath = '';
        if (!empty($_FILES['profile_path']['name'])) {
            $filename = time() . "_" . $_FILES['profile_path']['name'];
            $destination = "../uploads/" . $filename;
            if (move_uploaded_file($_FILES['profile_path']['tmp_name'], $destination)) {
                $profilePath = $filename;
            }
        }

        $userData['profile_path'] = $profilePath;

        $user = new User();

        if ($user->findByEmail($userData['email'])) {
            echo json_encode(['status' => 'error', 'message' => "Email already exists."]);
        } else {
            $success = $user->registerUser($userData);
            echo json_encode([
                'status' => $success ? 'success' : 'error',
                'message' => $success ? "Registration successful." : "Registration failed."
            ]);
        }


    }

    public function loginUser()
    {
        $userData = [
            'email' => $_POST['email'],
            'password' => $_POST['password'],
        ];

        $user = new User();
        $existingUser = $user->findByEmail($userData['email']);

        if ($existingUser && password_verify($userData['password'], $existingUser['password'])) {
            session_start();
            $_SESSION['user_id'] = $existingUser['id'];
            $_SESSION['first_name'] = $existingUser['first_name'];
            $_SESSION['profile_image'] = $existingUser['profile_path'];
            $_SESSION['role'] = $existingUser['role'];

            echo json_encode(['status' => 'success', 'message' => 'Login Successful!', 'role' => $existingUser['role']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Invalid email or password."]);
        }
    }

    public function getAllStudents()
    {
        $user = new User();
        $students = $user->getAllStudents();

        if ($students) {
            echo json_encode($students);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch students']);
        }
    }

    public function getStudentCount()
    {
        $count = new User();
        $studentCount = $count->getStudentCount();

        if ($studentCount) {
            echo json_encode(['status' => 'success', 'message' => $studentCount]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error getting student count.']);
        }
    }
}

$userController = new UserController();
$userController->processAction();

?>