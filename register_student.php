<?php
session_start();
require_once 'includes/db.php';

$name = $regNo = $age = $email = $phone = $course = "";
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $regNo = trim($_POST["reg_no"]);
    $age = (int)$_POST["age"];
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $course = trim($_POST["course"]);

    // Validations
    if (!preg_match("/^[a-zA-Z ]{2,}$/", $name)) {
        $errors[] = "Name must be at least 2 letters.";
    }

    if (!preg_match("/^REG-\d{4}-\d{4}$/", $regNo)) {
        $errors[] = "Registration Number must be REG-YYYY-NNNN.";
    }

    if ($age < 18 || $age > 25) {
        $errors[] = "Age must be between 18 and 25.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!preg_match("/^\d{10}$/", $phone)) {
        $errors[] = "Phone must be 10 digits.";
    }

    $validCourses = ["BTech CS", "BBA", "BCom", "BA"];
    if (!in_array($course, $validCourses)) {
        $errors[] = "Invalid course selected.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO students (name, reg_number, age, email, phone, course, registered_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssisss", $name, $regNo, $age, $email, $phone, $course);

        if ($stmt->execute()) {
            $success = "Student registered successfully!";
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="card-title text-center">Register New Student</h3>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php foreach ($errors as $err): ?>
                <div class="alert alert-danger"><?php echo $err; ?></div>
            <?php endforeach; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label>Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Registration No:</label>
                    <input type="text" name="reg_no" class="form-control" placeholder="REG-2025-0001" required>
                </div>
                <div class="mb-3">
                    <label>Age:</label>
                    <input type="number" name="age" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Phone:</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Course:</label>
                    <select name="course" class="form-control" required>
                        <option value="">Select Course</option>
                        <option value="BTech CS">BTech CS</option>
                        <option value="BBA">BBA</option>
                        <option value="BCom">BCom</option>
                        <option value="BA">BA</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
