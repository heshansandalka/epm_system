<?php
session_start();
include 'db_connect.php';

// Checking if you are an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') { 
    header("Location: index.php"); 
    exit(); 
}

$message = "";
if (isset($_POST['register'])) {
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST['full_name'], $_POST['email'], $pass, $_POST['role']);
    if ($stmt->execute()) { $message = "Registered successfully!"; }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">EPM ADMIN</div>
    <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="register_employee.php" class="active"><i class="fas fa-user-plus"></i> Register Employee</a>
    <a href="manage_projects.php"><i class="fas fa-tasks"></i> Projects</a>
    <a href="logout.php" style="color: #ff7675;"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <div class="card p-4 mx-auto shadow" style="max-width: 600px; margin-top: 50px;">
        <h4 class="mb-4">Add New Employee</h4>
        
        <?php if($message) echo "<div class='alert alert-success'>$message</div>"; ?>
        
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="mb-3">
                <select name="role" class="form-select">
                    <option value="Employee">Employee</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <button type="submit" name="register" class="btn btn-primary w-100">Create Account</button>
        </form>
    </div>
</div>

</body>
</html>