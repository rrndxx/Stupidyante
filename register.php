<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register | Stupidyante</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="logo.svg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right top, #e3f2fd, #f8f9fa);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .glass-card {
            backdrop-filter: blur(14px);
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 1rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 800px;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-control,
        .input-group-text,
        select {
            border-radius: .5rem;
            transition: border-color .3s ease, box-shadow .3s ease;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15);
        }

        .btn-dark {
            border-radius: .5rem;
            transition: background-color .3s ease;
            position: relative;
        }

        .btn-dark:hover {
            background-color: #000;
        }

        .title {
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1.2rem;
            color: #212529;
        }

        .brand-logo {
            display: block;
            width: 60px;
            height: 60px;
            margin: 0 auto .5rem;
            object-fit: contain;
        }

        .toast-custom {
            background-color: #212529;
            color: #fff;
            border-radius: .5rem;
        }

        a {
            color: #0d6efd;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .spinner-hidden {
            display: none;
        }
    </style>
</head>

<body>

    <div class="glass-card">
        <!-- Branding -->
        <img src="logo.svg" alt="Stupidyante Logo" class="brand-logo">
        <div class="title">Register</div>

        <form id="registerForm">
            <!-- Two-column grid -->
            <div class="row g-3">
                <!-- Column 1 -->
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                        <select name="gender" class="form-control" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="phone_number" class="form-control" placeholder="Phone Number">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                        <input type="text" name="course" class="form-control" placeholder="Course">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        <input type="date" name="birthdate" class="form-control" required>
                    </div>
                </div>

                <!-- Address spans full width -->
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <textarea name="address" class="form-control" placeholder="Address"></textarea>
                    </div>
                </div>

                <!-- File input full width -->
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-image"></i></span>
                        <input type="file" name="profile_path" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-dark w-100 mt-4" id="registerBtn">
                <span id="registerText">Register</span>
                <span id="registerSpinner" class="spinner-border spinner-border-sm spinner-hidden ms-2" role="status"
                    aria-hidden="true"></span>
            </button>

            <div class="text-center mt-3">
                Already have an account? <a href="login.php">Login</a>
            </div>
        </form>
    </div>

    <!-- Toast Container -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1055;">
        <div id="toastMsg" class="toast toast-custom align-items-center border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastBody"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="register.js"></script>

</body>

</html>