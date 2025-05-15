<?php
include_once '../../includes/sessionchecker.php';
studentCheck();

$profileImage = $_SESSION['profile_image'] ?? 'default.jpg';
$firstName = $_SESSION['first_name'] ?? 'Student';
$userId = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
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
                    <h4 class="text-center mt-4">Student Panel</h4>
                </div>

                <div class="bottom mb-3 w-100">
                    <div class="profile my-3 text-center">
                        <img src="../../uploads/<?= htmlspecialchars($profileImage) ?>" alt="Profile Picture"
                            class="mb-2">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <h5 class="mb-0"><?= htmlspecialchars($firstName) ?></h5>
                        </div>
                    </div>
                    <a class='border-top border-white pt-4' href="../../includes/logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>

            <div class="col-md-10 content">
                <h3>Welcome, <?= htmlspecialchars($firstName) ?></h3>

                <div class="row mt-4 justify-content-between text-center">
                    <div class="col-md-4">
                        <div class="card border-dark bg-dark text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Assigned Tasks</h5>
                                <p class="card-text p-4" id="totalAssignedTasks"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-dark bg-dark text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Pending Tasks</h5>
                                <p class="card-text p-4" id="totalPendingTasks"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-dark bg-dark text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Completed Tasks</h5>
                                <p class="card-text p-4" id="totalCompletedTasks"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ASSIGNED TASKS -->
                <div class="row table-container">
                    <div class="col-12">
                        <div class="card border-dark">
                            <div class="card-body">
                                <h5 class="card-title mb-4">My Tasks</h5>
                                <table class="table table-bordered table-striped">
                                    <thead class="text-center">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Due Date</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="assignedTaskTableBody" class="text-center">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="dashboard.js"></script>
</body>

</html>