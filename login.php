<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            color: black;
        }

        .form-control,
        .btn {
            border-color: black;
        }

        .btn {
            color: white;
            background-color: black;
        }

        .input-group-text {
            background-color: white;
            border-color: black;
            color: black;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="w-100" style="max-width: 600px;">
        <form id="loginForm" class="border p-4 rounded">
            <h4 class="text-center mb-4">Login</h4>

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn w-100 mt-2">Login</button>
            <div class="w-100 text-center mt-3">
                <a href="register.php">Register</a>
            </div>
            <div id="loginMsg" class="mt-2 text-center"></div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="login.js"></script>
</body>

</html>