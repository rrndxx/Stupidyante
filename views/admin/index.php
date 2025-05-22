<?php
include_once '../../includes/sessionchecker.php';
adminCheck();

$profileImage = $_SESSION['profile_image'];
$firstName = $_SESSION['first_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="../../logo.svg" type="image/x-icon" style="fill: white;" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Desktop Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16.6667%;
            background: linear-gradient(to bottom right, #212529, #343a40);
            color: white;
            padding: 2rem 1rem;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 1040;
        }

        .sidebar .bottom {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            padding: 0 1rem;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .sidebar .logo img {
            width: 50px;
            height: auto;
            margin-bottom: 10px;
        }

        .profile {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fff;
            margin-bottom: 0.5rem;
        }

        .profile h5 {
            font-size: 1rem;
            margin-bottom: 0;
            color: #e0e0e0;
        }

        .sidebar a {
            color: #ced4da;
            text-decoration: none;
            padding: 0.75rem 1rem;
            display: block;
            border-radius: 0.375rem;
            transition: background 0.2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #495057;
            color: white;
        }

        /* Main content area with sidebar margin */
        .content {
            margin-left: 16.6667%;
            padding: 2.5rem 3rem;
            background-color: #f5f7fa;
            transition: margin-left 0.3s ease;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        .card-dark {
            background-color: #343a40;
            color: #fff;
        }

        .btn-dark {
            background-color: #343a40;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-dark:hover {
            background-color: #212529;
        }

        .card-title {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .table-container {
            margin-top: 2rem;
        }

        .table {
            font-size: 0.95rem;
        }

        .table thead th {
            background-color: #212529;
            color: white;
            vertical-align: middle;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .toast {
            border-radius: 0.5rem;
            background-color: #343a40;
        }

        /* Hide desktop sidebar on small screens */
        @media (max-width: 767.98px) {
            .sidebar {
                display: none;
            }

            /* Content full width, remove left margin */
            .content {
                margin-left: 0 !important;
                padding: 1.5rem 1rem !important;
            }

            /* Mobile top bar */
            .mobile-topbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                /* Vertically center all items */
                padding: 0.75rem 1rem;
                background: linear-gradient(to right, #212529, #343a40);
                color: white;
                position: sticky;
                top: 0;
                z-index: 1050;
                gap: 1rem;
                /* optional: space between items */
            }

            .mobile-topbar .logo {
                display: flex;
                align-items: center;
            }

            .mobile-topbar .logo img {
                width: 40px;
                filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(180deg);
            }

            .mobile-topbar .profile {
                display: flex;
                align-items: center;
                cursor: pointer;
                gap: 0.5rem;
            }

            .mobile-topbar .profile img {
                width: 40px;
                height: 40px;
                object-fit: cover;
                border-radius: 50%;
                border: 2px solid white;
            }

            .mobile-topbar .logout-btn {
                color: white;
                font-size: 1.25rem;
                padding: 0.2rem 0.5rem;
                border: 1.5px solid white;
                border-radius: 0.375rem;
                transition: background 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mobile-topbar .logout-btn:hover {
                background-color: white;
                color: #212529;
                text-decoration: none;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile topbar for small devices -->
    <div class="mobile-topbar d-md-none">
        <div class="logo">
            <img src="../../logo.svg" alt="Logo">
        </div>
        <div class="profile">
            <img src=" ../../uploads/profiles/<?= htmlspecialchars($profileImage) ?>" alt="Profile Picture">
            <span><?= htmlspecialchars($firstName) ?></span>
        </div>
        <a href="../../includes/logout.php" class="logout-btn" title="Logout">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-none d-md-block">
                <div class="top">
                    <div class="logo">
                        <img src="../../logo.svg" alt="Logo"
                            style="filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(180deg);" />
                        <h4 class="text-white mt-2">Admin Dashboard</h4>
                    </div>
                    <div class="profile">
                        <img src="../../uploads/profiles/<?= htmlspecialchars($profileImage) ?>"
                            alt="Profile Picture" />
                        <h5><?= htmlspecialchars($firstName) ?></h5>
                    </div>
                </div>
                <div class="bottom">
                    <a class="d-block text-center py-2 mt-2 btn btn-outline-light" href="../../includes/logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-12 col-md-10 content">
                <h3 class="mb-4">Welcome back, <?= htmlspecialchars($firstName) ?></h3>

                <!-- Total Students Card -->
                <div class="row justify-content-center mb-5">
                    <div class="col-md-6 text-center">
                        <div class="card card-dark border-dark shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Total Students</h5>
                                <p class="card-text p-4 fs-3" id="totalStudents">--</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks Table -->
                <div class="row table-container">
                    <div class="col-12">
                        <div class="card border-dark shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3 align-items-center">
                                    <h5 class="card-title">Tasks</h5>
                                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                        <i class="bi bi-plus-lg"></i> Add Task
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="text-center table-dark">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Task Title</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Due Date</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tasksTableBody" class="text-center">
                                            <!-- JS Populated -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="row table-container">
                    <div class="col-12">
                        <div class="card border-dark shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Students</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="text-center table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Phone</th>
                                                <th>Course</th>
                                                <th>Address</th>
                                                <th>Birthdate</th>
                                            </tr>
                                        </thead>
                                        <tbody id="studentsTableBody" class="text-center">
                                            <!-- JS Populated -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Content -->
        </div>
    </div>

    <!-- Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
        <div id="toastMsg" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastBody"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <?php include '../../includes/addtaskmodal.php'; ?>
    <?php include '../../includes/edittaskmodal.php'; ?>
    <?php include '../../includes/submissionsmodal.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dashboard.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.card').forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transition = 'opacity 0.5s ease ' + (index * 0.2) + 's';
                setTimeout(() => card.style.opacity = '1', 100);
            });
        });


    </script>
</body>

</html>