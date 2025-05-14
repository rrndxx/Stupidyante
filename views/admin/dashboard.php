<?php
include_once '../../includes/sessionchecker.php';
adminCheck();

$profileImage = $_SESSION['profile_image'];
$firstName = $_SESSION['first_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-top: 20px;
        }

        .sidebar .top,
        .sidebar .bottom {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile {
            text-align: center;
        }

        .profile img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .profile h5 {
            margin-top: 10px;
            color: #fff;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            width: 100%;
            text-align: center;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            padding: 20px;
        }

        .btn {
            background-color: #343a40;
            color: white;
        }

        .table-container {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <div class="top">
                    <h4 class="text-center mt-4">Admin Panel</h4>
                </div>

                <div class="bottom mb-3 w-100">
                    <div class="profile my-3 text-center">
                        <img src="../../uploads/<?= htmlspecialchars($profileImage) ?>" alt="Profile Picture"
                            class="mb-2">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <h5 class="mb-0"><?= htmlspecialchars($firstName) ?></h5>
                        </div>
                    </div>
                    <a class='border-top border-white pt-4' href="../../includes/logout.php" class="text-center"><i
                            class="bi bi-box-arrow-right"></i>
                        Logout</a>
                </div>
            </div>

            <div class="col-md-10 content">
                <h3>Welcome, <?= htmlspecialchars($firstName) ?></h3>
                <p>This is your dashboard. You can manage users and tasks from here.</p>

                <div class="row mt-4 justify-content-center">
                    <div class="col-md-6 text-center">
                        <div class="card border-dark bg-dark text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Students</h5>
                                <p class="card-text p-4" id="totalStudents"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TASKS -->
                <div class="row table-container">
                    <div class="col-12">
                        <div class="card border-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="card-title">Tasks</h5>
                                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                        <i class="bi bi-plus-lg"></i> Add Task
                                    </button>
                                </div>
                                <table class="table table-bordered table-striped">
                                    <thead class="text-center">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Task Title</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Due Date</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tasksTableBody" class="text-center">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STUDENTS -->
                <div class="row table-container">
                    <div class="col-12">
                        <div class="card border-dark">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Students</h5>
                                <table class="table table-bordered table-striped">
                                    <thead class="text-center">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">First Name</th>
                                            <th scope="col">Last Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Gender</th>
                                            <th scope="col">Phone Number</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">Birthdate</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentsTableBody" class="text-center">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../../includes/addtaskmodal.php'; ?>
    <?php include '../../includes/edittaskmodal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="dashboard.js"></script>
</body>

</html>