<?php
session_start();

// Track failed login attempts in session
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

$error = "";
$blocked = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $now = time();

    // Check if blocked
    if ($_SESSION['attempts'] >= 3 && ($now - $_SESSION['last_attempt_time']) < 300) {
        $blocked = true;
        $error = "Too many failed attempts. Try again after 5 minutes.";
    } else {
        // Login check: hardcoded username/password
        if ($username === 'admin' && $password === 'admin') {
            $_SESSION['logged_in'] = true;
            $_SESSION['attempts'] = 0; // Reset attempts
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['attempts']++;
            $_SESSION['last_attempt_time'] = $now;
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login (Bootstrap)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card shadow p-4" style="width: 350px;">
    <h3 class="text-center mb-3">Admin Login</h3>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <p class="text-danger text-center mt-2"><?php echo $error; ?></p>
</div>
</body>
</html>