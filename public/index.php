<?php
session_start();

// If user is already logged in, redirect to appropriate dashboard
if (isset($_SESSION['user_id'])) {
    require_once '../app/models/User.php';
    $userModel = new User();
    
    if ($userModel->isAdmin($_SESSION['user_id'])) {
        header('Location: admin_dashboard.php');
    } else {
        header('Location: student_dashboard.php');
    }
    exit();
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    require_once '../app/models/User.php';
    $userModel = new User();
    
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];
    
    $user = $userModel->authenticate($email, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        
        // Redirect based on role
        if ($userModel->isAdmin($user['id'])) {
            header('Location: admin_dashboard.php');
        } else {
            header('Location: student_dashboard.php');
        }
        exit();
    } else {
        $loginError = 'Invalid email or password';
    }
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    require_once '../app/models/User.php';
    $userModel = new User();
    
    $data = [
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName'],
        'email' => $_POST['email'],
        'gender' => $_POST['gender'],
        'phone' => $_POST['phone'],
        'course' => $_POST['course'],
        'address' => $_POST['address'],
        'birthdate' => $_POST['birthdate'],
        'password' => $_POST['password'],
        'role' => 'student' // Default role for new registrations
    ];
    
    // Handle profile picture upload
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/profiles/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . basename($_FILES['profile']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['profile']['tmp_name'], $targetPath)) {
            $data['profile_picture'] = $fileName;
        }
    }
    
    if ($userModel->create($data)) {
        $registerSuccess = 'Registration successful! Please login.';
    } else {
        $registerError = 'Registration failed. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register | Stupidyante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b00;
            --primary-light: #ff8533;
            --primary-dark: #e65c00;
        }
        
        body {
            background: linear-gradient(135deg, #fff5eb 0%, #ffe4cc 100%);
        }
        
        .orange-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        }
        
        .orange-hover:hover {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 0, 0.3);
        }
        
        .form-container {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #ff8533;
        }
        
        .input-field {
            padding-left: 40px;
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
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .card-shadow {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-4xl w-full form-container rounded-xl card-shadow p-6 md:p-8 animate-fade-in">
            <!-- Logo -->
            <div class="text-center mb-8">
                <h1 class="logo">Stupidyante</h1>
                <p class="text-gray-500 mt-2">Student Management System</p>
            </div>
            
            <!-- Tabs -->
            <div class="flex mb-8 border-b border-gray-200">
                <button id="loginTab" class="flex-1 py-3 text-center tab-active text-orange-500 font-medium transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
                <button id="registerTab" class="flex-1 py-3 text-center text-gray-500 font-medium transition duration-200">
                    <i class="fas fa-user-plus mr-2"></i>Register
                </button>
            </div>

            <!-- Login Form -->
            <div id="loginForm" class="space-y-6">
                <?php if (isset($loginError)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo $loginError; ?></span>
                    </div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="input-group mb-4">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="loginEmail" name="loginEmail" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" placeholder="Enter your email" required>
                    </div>
                    <div class="input-group mb-4">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="loginPassword" name="loginPassword" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" placeholder="Enter your password" required>
                    </div>
                    <div class="flex items-center justify-between text-sm mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2 text-orange-500">
                            <span class="text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-orange-500 hover:text-orange-600">Forgot password?</a>
                    </div>
                    <button type="submit" class="w-full orange-gradient text-white py-3 rounded-lg orange-hover transition duration-200 font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>
                </form>
            </div>

            <!-- Register Form -->
            <div id="registerForm" class="hidden space-y-6">
                <?php if (isset($registerSuccess)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo $registerSuccess; ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($registerError)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo $registerError; ?></span>
                    </div>
                <?php endif; ?>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="register">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div class="input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="firstName" name="firstName" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" placeholder="First Name" required>
                        </div>
                        <div class="input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="lastName" name="lastName" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" placeholder="Email Address" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div class="input-group">
                            <i class="fas fa-venus-mars input-icon"></i>
                            <select id="gender" name="gender" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <i class="fas fa-phone input-icon"></i>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" placeholder="Phone Number" required>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <i class="fas fa-graduation-cap input-icon"></i>
                        <input type="text" id="course" name="course" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" placeholder="Course" required>
                    </div>
                    <div class="input-group mb-4">
                        <i class="fas fa-map-marker-alt input-icon"></i>
                        <textarea id="address" name="address" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" rows="2" placeholder="Address" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div class="input-group">
                            <i class="fas fa-calendar input-icon"></i>
                            <input type="date" id="birthdate" name="birthdate" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" required>
                        </div>
                        <div class="input-group">
                            <i class="fas fa-image input-icon"></i>
                            <input type="file" id="profile" name="profile" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" accept="image/*" required>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-orange-500 transition duration-200 input-field" placeholder="Password" required>
                    </div>
                    <button type="submit" class="w-full orange-gradient text-white py-3 rounded-lg orange-hover transition duration-200 font-medium">
                        <i class="fas fa-user-plus mr-2"></i>Register
                    </button>
                </form>
            </div>
            
            <!-- Social Login -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-gray-500 mb-4">Or continue with</p>
                <div class="flex justify-center space-x-4">
                    <button class="w-10 h-10 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition duration-200">
                        <i class="fab fa-google text-red-500"></i>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition duration-200">
                        <i class="fab fa-facebook-f text-blue-600"></i>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition duration-200">
                        <i class="fab fa-twitter text-blue-400"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Tab switching
            $('#loginTab').click(function() {
                $('#loginTab').addClass('tab-active text-orange-500').removeClass('text-gray-500');
                $('#registerTab').removeClass('tab-active text-orange-500').addClass('text-gray-500');
                $('#loginForm').removeClass('hidden');
                $('#registerForm').addClass('hidden');
            });
            
            $('#registerTab').click(function() {
                $('#registerTab').addClass('tab-active text-orange-500').removeClass('text-gray-500');
                $('#loginTab').removeClass('tab-active text-orange-500').addClass('text-gray-500');
                $('#registerForm').removeClass('hidden');
                $('#loginForm').addClass('hidden');
            });
        });
    </script>
</body>
</html> 