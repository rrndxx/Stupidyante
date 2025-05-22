<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Stupidyante</title>
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
            max-width: 420px;
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
        .input-group-text {
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .btn-dark {
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
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
            object-fit: contain;
            margin: 0 auto 0.5rem;
        }

        .toggle-password {
            cursor: pointer;
            user-select: none;
        }

        .toast-custom {
            background-color: #212529;
            color: white;
            border-radius: 0.5rem;
        }

        .text-link {
            font-size: 0.9rem;
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

    <div class="glass-card" style="min-width: 600px">
        <!-- Branding -->
        <img src="logo.svg" alt="Stupidyante Logo" class="brand-logo" />
        <div class="title">Login</div>

        <form id="loginForm">
            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required
                    id="passwordField">
                <span class="input-group-text toggle-password" onclick="togglePassword()"><i
                        class="bi bi-eye"></i></span>
            </div>

            <button type="submit" class="btn btn-dark w-100 mt-3" id="loginBtn">
                <span id="loginText">Login</span>
                <span id="loginSpinner" class="spinner-border spinner-border-sm spinner-hidden ms-2" role="status"
                    aria-hidden="true"></span>
            </button>

            <div class="text-center mt-3 text-link">
                Don't have an account? <a href="register.php">Register</a>
            </div>
        </form>
    </div>

    <!-- Toast Container -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
        <div id="toastMsg" class="toast toast-custom align-items-center border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastBody"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <?php include_once 'views/auth/two-step-verification.php' ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="login.js"></script>

    <script>
        function togglePassword() {
            const passField = document.getElementById('passwordField');
            const toggleIcon = event.currentTarget.querySelector('i');
            if (passField.type === "password") {
                passField.type = "text";
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passField.type = "password";
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        document.getElementById("loginForm").addEventListener("submit", function (e) {
            e.preventDefault(); // Remove this line when integrating real backend

            const loginBtn = document.getElementById("loginBtn");
            const spinner = document.getElementById("loginSpinner");
            const loginText = document.getElementById("loginText");

            spinner.classList.remove("spinner-hidden");
            loginBtn.disabled = true;

            // Simulate login processing
            setTimeout(() => {
                spinner.classList.add("spinner-hidden");
                loginBtn.disabled = false;
                showToast("Login successful!"); // You can adjust this to use actual login logic
            }, 2000);
        });

        function showToast(message) {
            const toastBody = document.getElementById("toastBody");
            toastBody.textContent = message;

            const toast = new bootstrap.Toast(document.getElementById("toastMsg"));
            toast.show();
        }
    </script>

</body>

</html>