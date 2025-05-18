<?php
require_once '../../models/UserModel.php';

$result = '';
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $verify = new User();
    $result = $verify->verifyUser($token);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: white;
            color: black;
        }

        .form-box {
            border: 1px solid black;
            padding: 2rem;
            border-radius: 8px;
        }

        .btn {
            background-color: black;
            color: white;
        }

        .message {
            font-size: 1rem;
            margin-top: 1rem;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        .message.warning {
            color: orange;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="w-100" style="max-width: 600px;">
        <div class="form-box text-center">
            <h4 class="mb-3">Email Verification</h4>
            <div class="message 
                <?php
                if ($result === true) {
                    echo 'success';
                } elseif ($result === false) {
                    echo 'error';
                } else {
                    echo 'warning';
                }
                ?>">
                <?php
                if ($result === true) {
                    echo "✅ Your email has been successfully verified.";
                } elseif ($result === false) {
                    echo "❌ Invalid or expired verification link.";
                } else {
                    echo "⏳ Verifying...";
                }
                ?>
            </div>
            <p class="mt-3">Redirecting to login in 3 seconds...</p>
            <a href="../../login.php" class="btn mt-2">Go to Login Now</a>
        </div>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = '../../login.php';
        }, 3000);
    </script>

</body>

</html>