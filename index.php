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
    <title>Admin Login</title>
</head>
<body>
<h2>Login</h2>
<form method="POST" action="">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit" <?php if($blocked) echo 'disabled'; ?>>Login</button>
</form>

<p style="color:red;"><?php echo $error; ?></p>
</body>
</html>
