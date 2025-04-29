<?php
session_start();

// Check if user is logged in and is an admin
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

// If user data is not found or user is not an admin, redirect to login
if (!$user || !$userModel->isAdmin($_SESSION['user_id'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Get all students
$allStudents = $userModel->getAllStudents();

// Get all tasks
$allTasks = $taskController->getAllTasks();

// Get active tab from URL parameter
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'students';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View | Stupidyante</title>
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
        
        .tab-active {
            position: relative;
        }
        
        .tab-active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            border-radius: 3px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 p-6 flex flex-col">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-orange-500">Stupidyante</h1>
                <p class="text-sm text-gray-500">Admin View</p>
            </div>
            
            <nav class="flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="admin_dashboard.php" class="flex items-center p-3 text-gray-600 hover:bg-white hover:text-orange-500 rounded-lg transition duration-200">
                            <i class="fas fa-home mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_view.php?tab=students" class="flex items-center p-3 <?php echo $activeTab === 'students' ? 'text-orange-500 bg-white rounded-lg shadow-sm' : 'text-gray-600 hover:bg-white hover:text-orange-500 rounded-lg transition duration-200'; ?>">
                            <i class="fas fa-users mr-3"></i>
                            <span>Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_view.php?tab=tasks" class="flex items-center p-3 <?php echo $activeTab === 'tasks' ? 'text-orange-500 bg-white rounded-lg shadow-sm' : 'text-gray-600 hover:bg-white hover:text-orange-500 rounded-lg transition duration-200'; ?>">
                            <i class="fas fa-tasks mr-3"></i>
                            <span>Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_view.php?tab=submissions" class="flex items-center p-3 <?php echo $activeTab === 'submissions' ? 'text-orange-500 bg-white rounded-lg shadow-sm' : 'text-gray-600 hover:bg-white hover:text-orange-500 rounded-lg transition duration-200'; ?>">
                            <i class="fas fa-file-upload mr-3"></i>
                            <span>Submissions</span>
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
                    <h2 class="text-xl font-semibold text-gray-800">Admin View</h2>
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
            
            <!-- Content Area -->
            <div class="p-6">
                <!-- Tabs -->
                <div class="flex mb-6 border-b border-gray-200">
                    <a href="?tab=students" class="py-3 px-4 text-center tab-active <?php echo $activeTab === 'students' ? 'text-orange-500 font-medium' : 'text-gray-500'; ?>">
                        <i class="fas fa-users mr-2"></i>Students
                    </a>
                    <a href="?tab=tasks" class="py-3 px-4 text-center <?php echo $activeTab === 'tasks' ? 'text-orange-500 font-medium tab-active' : 'text-gray-500'; ?>">
                        <i class="fas fa-tasks mr-2"></i>Tasks
                    </a>
                    <a href="?tab=submissions" class="py-3 px-4 text-center <?php echo $activeTab === 'submissions' ? 'text-orange-500 font-medium tab-active' : 'text-gray-500'; ?>">
                        <i class="fas fa-file-upload mr-2"></i>Submissions
                    </a>
                </div>
                
                <!-- Students Tab -->
                <?php if ($activeTab === 'students'): ?>
                    <div class="bg-white p-6 rounded-xl card-shadow">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">All Students</h3>
                            <div class="relative">
                                <input type="text" id="studentSearch" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500" placeholder="Search students...">
                                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (empty($allStudents)): ?>
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No students found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($allStudents as $student): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo str_pad($student['id'], 8, '0', STR_PAD_LEFT); ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <img src="<?php echo htmlspecialchars($student['profile_picture'] ?? 'https://via.placeholder.com/40'); ?>" alt="Profile" class="w-8 h-8 rounded-full mr-2">
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></div>
                                                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($student['gender']); ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($student['email']); ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($student['course']); ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($student['phone']); ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="?tab=submissions&student_id=<?php echo $student['id']; ?>" class="text-orange-500 hover:text-orange-700 mr-3">
                                                        <i class="fas fa-file-alt"></i> View Submissions
                                                    </a>
                                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Tasks Tab -->
                <?php if ($activeTab === 'tasks'): ?>
                    <div class="bg-white p-6 rounded-xl card-shadow">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">All Tasks</h3>
                            <a href="admin_dashboard.php" class="bg-orange-500 text-white py-2 px-4 rounded-lg hover:bg-orange-600 transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Create New Task
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (empty($allTasks)): ?>
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No tasks found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($allTasks as $task): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo str_pad($task['id'], 8, '0', STR_PAD_LEFT); ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($task['title']); ?></td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    <div class="max-w-xs truncate"><?php echo htmlspecialchars($task['description']); ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php 
                                                    $deadline = new DateTime($task['deadline']);
                                                    $now = new DateTime();
                                                    $interval = $now->diff($deadline);
                                                    $isOverdue = $now > $deadline;
                                                    
                                                    echo $deadline->format('M d, Y h:i A');
                                                    if ($isOverdue) {
                                                        echo ' <span class="text-red-500">(Overdue)</span>';
                                                    } elseif ($interval->days <= 3) {
                                                        echo ' <span class="text-orange-500">(Due soon)</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 py-1 text-xs rounded-full 
                                                        <?php 
                                                        switch($task['status']) {
                                                            case 'pending':
                                                                echo 'bg-yellow-100 text-yellow-800';
                                                                break;
                                                            case 'in_progress':
                                                                echo 'bg-blue-100 text-blue-800';
                                                                break;
                                                            case 'completed':
                                                                echo 'bg-green-100 text-green-800';
                                                                break;
                                                            default:
                                                                echo 'bg-gray-100 text-gray-800';
                                                        }
                                                        ?>">
                                                        <?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php 
                                                    $assignedStudents = $taskController->getAssignedStudents($task['id']);
                                                    echo count($assignedStudents) . ' student(s)';
                                                    ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="?tab=submissions&task_id=<?php echo $task['id']; ?>" class="text-orange-500 hover:text-orange-700 mr-3">
                                                        <i class="fas fa-file-alt"></i> View Submissions
                                                    </a>
                                                    <a href="#" class="text-blue-500 hover:text-blue-700">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Submissions Tab -->
                <?php if ($activeTab === 'submissions'): ?>
                    <div class="bg-white p-6 rounded-xl card-shadow">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Task Submissions</h3>
                            <div class="flex space-x-4">
                                <select id="taskFilter" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                                    <option value="">All Tasks</option>
                                    <?php foreach ($allTasks as $task): ?>
                                        <option value="<?php echo $task['id']; ?>" <?php echo (isset($_GET['task_id']) && $_GET['task_id'] == $task['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($task['title']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <select id="studentFilter" class="px-4 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                                    <option value="">All Students</option>
                                    <?php foreach ($allStudents as $student): ?>
                                        <option value="<?php echo $student['id']; ?>" <?php echo (isset($_GET['student_id']) && $_GET['student_id'] == $student['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <?php
                        // Get submissions based on filters
                        $taskId = isset($_GET['task_id']) ? $_GET['task_id'] : null;
                        $studentId = isset($_GET['student_id']) ? $_GET['student_id'] : null;
                        
                        if ($taskId) {
                            $submissions = $taskSubmissionModel->getSubmissionsForTask($taskId);
                        } elseif ($studentId) {
                            // This method needs to be implemented in TaskSubmission model
                            $submissions = $taskSubmissionModel->getSubmissionsByStudent($studentId);
                        } else {
                            // Get all submissions
                            $submissions = $taskSubmissionModel->getAllSubmissions();
                        }
                        ?>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feedback</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (empty($submissions)): ?>
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No submissions found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($submissions as $submission): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo str_pad($submission['id'], 8, '0', STR_PAD_LEFT); ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <?php 
                                                    $task = $taskController->getTaskById($submission['task_id']);
                                                    echo htmlspecialchars($task['title']); 
                                                    ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <?php 
                                                        $student = $userModel->findById($submission['student_id']);
                                                        ?>
                                                        <img src="<?php echo htmlspecialchars($student['profile_picture'] ?? 'https://via.placeholder.com/40'); ?>" alt="Profile" class="w-8 h-8 rounded-full mr-2">
                                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php 
                                                    $submissionDate = new DateTime($submission['submission_date']);
                                                    echo $submissionDate->format('M d, Y h:i A'); 
                                                    ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 py-1 text-xs rounded-full 
                                                        <?php 
                                                        switch($submission['status']) {
                                                            case 'submitted':
                                                                echo 'bg-blue-100 text-blue-800';
                                                                break;
                                                            case 'reviewed':
                                                                echo 'bg-green-100 text-green-800';
                                                                break;
                                                            case 'rejected':
                                                                echo 'bg-red-100 text-red-800';
                                                                break;
                                                            default:
                                                                echo 'bg-gray-100 text-gray-800';
                                                        }
                                                        ?>">
                                                        <?php echo ucfirst($submission['status']); ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    <div class="max-w-xs truncate"><?php echo htmlspecialchars($submission['feedback'] ?? 'No feedback yet'); ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="../uploads/<?php echo htmlspecialchars($submission['file_path']); ?>" target="_blank" class="text-orange-500 hover:text-orange-700 mr-3">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                    <a href="#" class="text-blue-500 hover:text-blue-700" onclick="showFeedbackModal(<?php echo $submission['id']; ?>, '<?php echo htmlspecialchars($submission['feedback'] ?? ''); ?>', '<?php echo $submission['status']; ?>')">
                                                        <i class="fas fa-comment"></i> Feedback
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Feedback Modal -->
                    <div id="feedbackModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Provide Feedback</h3>
                                <form id="feedbackForm" action="update_feedback.php" method="POST">
                                    <input type="hidden" id="submissionId" name="submission_id">
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="feedback">Feedback</label>
                                        <textarea id="feedback" name="feedback" rows="4" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500" required></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Status</label>
                                        <select id="status" name="status" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500" required>
                                            <option value="reviewed">Reviewed</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" onclick="closeFeedbackModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-200">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Filter submissions by task or student
        document.getElementById('taskFilter').addEventListener('change', function() {
            const taskId = this.value;
            const studentId = document.getElementById('studentFilter').value;
            
            let url = '?tab=submissions';
            if (taskId) url += '&task_id=' + taskId;
            if (studentId) url += '&student_id=' + studentId;
            
            window.location.href = url;
        });
        
        document.getElementById('studentFilter').addEventListener('change', function() {
            const studentId = this.value;
            const taskId = document.getElementById('taskFilter').value;
            
            let url = '?tab=submissions';
            if (taskId) url += '&task_id=' + taskId;
            if (studentId) url += '&student_id=' + studentId;
            
            window.location.href = url;
        });
        
        // Feedback modal functions
        function showFeedbackModal(submissionId, feedback, status) {
            document.getElementById('submissionId').value = submissionId;
            document.getElementById('feedback').value = feedback;
            document.getElementById('status').value = status;
            document.getElementById('feedbackModal').classList.remove('hidden');
        }
        
        function closeFeedbackModal() {
            document.getElementById('feedbackModal').classList.add('hidden');
        }
    </script>
</body>
</html> 