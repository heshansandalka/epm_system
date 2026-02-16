<?php
session_start();
include 'db_connect.php'; // Database connection

// Verifying that you are an employee
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id']; // The ID of the currently logged in employee

// 1. Get the number of projects assigned to the relevant employee
$proj_res = $conn->query("SELECT COUNT(DISTINCT project_id) as count FROM project_assignments WHERE user_id = $user_id");
$assigned_projs = $proj_res->fetch_assoc()['count'];

// 2. Get the number of unfinished tasks of the relevant employee (if there is a table called tasks)
// For now, I will leave it as 0 for example, you can change this depending on the table.
$pending_tasks = 0; 
?>

<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Employee Dashboard</title>
</head>
<body>

<div class="sidebar" style="background: linear-gradient(180deg, #1cc88a 0%, #13855c 100%);">
    <div class="sidebar-header">EPM WORKER</div>
    <a href="employee_dashboard.php"><i class="fas fa-home"></i> My Dashboard</a>
    <a href="view_tasks.php"><i class="fas fa-list-check"></i> My Tasks</a>
    <a href="submit_log.php"><i class="fas fa-edit"></i> Submit Work Log</a>
    <a href="change_password.php"><i class="fas fa-key"></i> Change Password</a> <a href="logout.php" style="color: #ff7675;"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h2>Employee Portal</h2>
        <span>Welcome back!</span>
    </div>

    <div class="stats-grid" style="margin-top: 20px;">
        <div class="stat-card" style="border-left-color: #1cc88a;">
            <h3>Assigned Projects</h3>
            <p style="font-size: 2rem;"><?php echo $assigned_projs; ?></p>
        </div>
        <div class="stat-card" style="border-left-color: #3498db;">
            <h3>Pending Tasks</h3>
            <p style="font-size: 2rem;"><?php echo $pending_tasks; ?></p>
        </div>
    </div>

    <div class="form-container" style="max-width: 100%;">
        <h3>Today's Focus</h3>
        <p>Update your daily work logs using the system..</p>
    </div>
</div>

</body>
</html>