<?php
session_start();

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once '../app/models/User.php';
require_once '../app/controllers/TaskController.php';
require_once '../app/models/TaskSubmission.php';

$userModel = new User();
$taskController = new TaskController();
$taskSubmissionModel = new TaskSubmission();
$user = $userModel->findById($_SESSION['user_id']);

// If user data is not found or user is not a student, redirect to login
if (!$user || !$userModel->isStudent($_SESSION['user_id'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Handle file submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_task') {
    $taskId = $_POST['task_id'];
    
    // Handle file upload
    if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($_FILES['submission_file']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['submission_file']['tmp_name'], $targetPath)) {
            $data = [
                'task_id' => $taskId,
                'student_id' => $_SESSION['user_id'],
                'file_path' => $fileName
            ];
            
            if ($taskSubmissionModel->submit($data)) {
                $_SESSION['success'] = 'Task submitted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to submit task.';
            }
        } else {
            $_SESSION['error'] = 'Failed to upload file.';
        }
    } else {
        $_SESSION['error'] = 'Please select a file to submit.';
    }
    
    header('Location: student_dashboard.php');
    exit();
}

// Get tasks assigned to the student
$assignedTasks = $taskController->getTasksForUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Stupidyante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b00;
            --primary-light: #ff8533;
            --primary-dark: #e65c00;
        }
        
        .orange-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        }
        
        .orange-hover:hover {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
        }
        
        .sidebar {
            background: linear-gradient(180deg, #fff5eb 0%, #ffe4cc 100%);
        }
        
        .card-shadow {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 p-6 flex flex-col">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-orange-500">Stupidyante</h1>
                <p class="text-sm text-gray-500">Student Dashboard</p>
            </div>
            
            <nav class="flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center p-3 text-orange-500 bg-white rounded-lg shadow-sm">
                            <i class="fas fa-home mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-white hover:text-orange-500 rounded-lg transition duration-200">
                            <i class="fas fa-tasks mr-3"></i>
                            <span>My Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-white hover:text-orange-500 rounded-lg transition duration-200">
                            <i class="fas fa-user mr-3"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-white hover:text-orange-500 rounded-lg transition duration-200">
                            <i class="fas fa-cog mr-3"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="pt-4 border-t border-gray-200">
                <a href="logout.php" class="flex items-center p-3 text-gray-600 hover:bg-white hover:text-orange-500 rounded-lg transition duration-200">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <div class="bg-white p-4 shadow-sm flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Student Dashboard</h2>
                    <p class="text-sm text-gray-500">Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
                </div>
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-bell text-gray-500 text-xl"></i>
                    </div>
                    <div class="flex items-center">
                        <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'https://via.placeholder.com/40'); ?>" alt="Profile" class="w-10 h-10 rounded-full mr-2">
                        <div>
                            <p class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dashboard Content -->
            <div class="p-6">
                <!-- Profile Header -->
                <div class="profile-header rounded-xl p-6 text-white mb-6">
                    <div class="flex items-center">
                        <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'https://via.placeholder.com/100'); ?>" alt="Profile" class="w-24 h-24 rounded-full border-4 border-white mr-6">
                        <div>
                            <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
                            <p class="text-lg opacity-90"><?php echo htmlspecialchars($user['course'] ?? 'Not specified'); ?></p>
                            <p class="text-sm opacity-80">Student ID: <?php echo str_pad($user['id'], 8, '0', STR_PAD_LEFT); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Assigned Tasks Section -->
                <div class="bg-white p-6 rounded-xl card-shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Assigned Tasks</h3>
                    <div class="space-y-4">
                        <?php if (empty($assignedTasks)): ?>
                            <p class="text-gray-500">No tasks assigned to you.</p>
                        <?php else: ?>
                            <?php foreach ($assignedTasks as $task): ?>
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-semibold"><?php echo htmlspecialchars($task['title']); ?></h4>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($task['description']); ?></p>
                                    <div class="mt-2 flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Deadline: <?php echo date('M d, Y h:i A', strtotime($task['deadline'])); ?></span>
                                        <span class="px-2 py-1 rounded-full text-xs <?php echo $task['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                            <?php echo ucfirst($task['status']); ?>
                                        </span>
                                    </div>
                                    
                                    <?php 
                                    $submission = $taskSubmissionModel->getSubmissionByStudentAndTask($_SESSION['user_id'], $task['id']);
                                    if ($submission): 
                                    ?>
                                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                            <p class="text-sm font-medium">Submission Status: 
                                                <span class="<?php echo $submission['status'] === 'reviewed' ? 'text-green-600' : ($submission['status'] === 'rejected' ? 'text-red-600' : 'text-blue-600'); ?>">
                                                    <?php echo ucfirst($submission['status']); ?>
                                                </span>
                                            </p>
                                            <?php if ($submission['feedback']): ?>
                                                <p class="text-sm text-gray-600 mt-1">Feedback: <?php echo htmlspecialchars($submission['feedback']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="mt-3">
                                            <form action="" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                                <input type="hidden" name="action" value="submit_task">
                                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                                <input type="file" name="submission_file" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" required>
                                                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200 text-sm">
                                                    Submit
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 