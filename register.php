<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
        <form id="registerForm" class="border p-4 rounded">
            <h4 class="text-center mb-4">Register</h4>

            <div class="row g-2">
                <div class="col-md-6 input-group mb-2">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                </div>
                <div class="col-md-6 input-group mb-2">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                </div>
            </div>

            <div class="input-group mb-2">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="input-group mb-2">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="input-group mb-2">
                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                <select name="gender" class="form-control" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <div class="input-group mb-2">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" name="phone_number" class="form-control" placeholder="Phone Number">
            </div>

            <div class="input-group mb-2">
                <span class="input-group-text"><i class="bi bi-journal-bookmark"></i></span>
                <input type="text" name="course" class="form-control" placeholder="Course">
            </div>

            <div class="input-group mb-2">
                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                <textarea name="address" class="form-control" placeholder="Address"></textarea>
            </div>

            <div class="input-group mb-2">
                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                <input type="date" name="birthdate" class="form-control" required>
            </div>

            <div class="input-group mb-2">
                <span class="input-group-text"><i class="bi bi-image"></i></span>
                <input type="file" name="profile_path" class="form-control">
            </div>

            <button type="submit" class="btn w-100 mt-2">Register</button>
            <div class="w-100 text-center mt-3">
                <a href="login.php">Login</a>
            </div>
            <div id="registerMsg" class="mt-2 text-center"></div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="register.js"></script>
</body>

</html>