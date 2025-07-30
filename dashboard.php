<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="card shadow p-4" style="max-width: 400px; width: 100%;">
    <h3 class="text-center mb-4">Welcome, Admin!</h3>
    <p class="text-center">You are logged in.</p>
    <div class="d-grid gap-2">
        <a href="register_student.php" class="btn btn-success">➕ Register Student</a>
        <a href="list_student.php" class="btn btn-primary">📋 List Students</a>
        <a href="logout.php" class="btn btn-danger">🔓 Logout</a>
    </div>
</div>
</body>
</html>
