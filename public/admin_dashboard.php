<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once '../app/models/User.php';
require_once '../app/controllers/TaskController.php';

$userModel = new User();
$taskController = new TaskController();
$user = $userModel->findById($_SESSION['user_id']);

// If user data is not found or user is not an admin, redirect to login
if (!$user || !$userModel->isAdmin($_SESSION['user_id'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Handle task creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_task'])) {
    $taskData = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'deadline' => $_POST['deadline'],
        'created_by' => $_SESSION['user_id'],
        'status' => 'pending'
    ];
    
    $studentIds = isset($_POST['assigned_students']) ? $_POST['assigned_students'] : [];
    
    if ($taskController->createTask($taskData, $studentIds)) {
        $successMessage = "Task created successfully!";
    } else {
        $errorMessage = "Failed to create task. Please try again.";
    }
}

// Get all students for task assignment
$allStudents = $userModel->getAllStudents();

// Get tasks created by the admin
$adminTasks = $taskController->getTasksCreatedByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Stupidyante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                <p class="text-sm text-gray-500">Admin Dashboard</p>
            </div>
            
            <nav class="flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="admin_dashboard.php" class="flex items-center p-3 text-orange-500 bg-white rounded-lg shadow-sm">
                            <i class="fas fa-home mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_view.php" class="flex items-center p-3 text-gray-600 hover:bg-white hover:text-orange-500 rounded-lg transition duration-200">
                            <i class="fas fa-users mr-3"></i>
                            <span>Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 text-gray-600 hover:bg-white hover:text-orange-500 rounded-lg transition duration-200">
                            <i class="fas fa-tasks mr-3"></i>
                            <span>Tasks</span>
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
                    <h2 class="text-xl font-semibold text-gray-800">Admin Dashboard</h2>
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
                <?php if (isset($successMessage)): ?>
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        <?php echo $successMessage; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($errorMessage)): ?>
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Profile Header -->
                <div class="profile-header rounded-xl p-6 text-white mb-6">
                    <div class="flex items-center">
                        <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'https://via.placeholder.com/100'); ?>" alt="Profile" class="w-24 h-24 rounded-full border-4 border-white mr-6">
                        <div>
                            <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
                            <p class="text-lg opacity-90">Administrator</p>
                            <p class="text-sm opacity-80">Admin ID: <?php echo str_pad($user['id'], 8, '0', STR_PAD_LEFT); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Task Management Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Create Task Form -->
                    <div class="bg-white p-6 rounded-xl card-shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Create New Task</h3>
                        <form action="" method="POST" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Task Title</label>
                                <input type="text" name="title" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" rows="3" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                                <input type="datetime-local" name="deadline" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Assign to Students</label>
                                <select name="assigned_students[]" multiple class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500" required>
                                    <?php foreach ($allStudents as $student): ?>
                                        <option value="<?php echo $student['id']; ?>">
                                            <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" name="create_task" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200">
                                    Create Task
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Task Lists -->
                    <div class="bg-white p-6 rounded-xl card-shadow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Tasks</h3>
                        <div class="space-y-4">
                            <?php if (empty($adminTasks)): ?>
                                <p class="text-gray-500">No tasks created yet.</p>
                            <?php else: ?>
                                <?php foreach ($adminTasks as $task): ?>
                                    <div class="border rounded-lg p-4">
                                        <h4 class="font-semibold"><?php echo htmlspecialchars($task['title']); ?></h4>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($task['description']); ?></p>
                                        <div class="mt-2 flex justify-between items-center text-sm">
                                            <span class="text-gray-500">Deadline: <?php echo date('M d, Y h:i A', strtotime($task['deadline'])); ?></span>
                                            <span class="text-gray-500">Assigned to: <?php echo $task['assigned_count']; ?> students</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Created Tasks Section -->
                <div class="bg-white p-6 rounded-xl card-shadow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">All Tasks</h3>
                    <div class="space-y-4">
                        <?php if (empty($adminTasks)): ?>
                            <p class="text-gray-500">You haven't created any tasks yet.</p>
                        <?php else: ?>
                            <?php foreach ($adminTasks as $task): ?>
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-semibold"><?php echo htmlspecialchars($task['title']); ?></h4>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($task['description']); ?></p>
                                    <div class="mt-2 flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Deadline: <?php echo date('M d, Y h:i A', strtotime($task['deadline'])); ?></span>
                                        <span class="text-gray-500">Assigned to: <?php echo $task['assigned_count']; ?> students</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('select[name="assigned_students[]"]').select2({
                placeholder: "Select students",
                allowClear: true
            });
        });
    </script>
</body>
</html> 