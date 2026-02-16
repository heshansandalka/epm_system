<?php
session_start();
include 'db_connect.php';

// Checking if the user is an Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') { 
    header("Location: index.php"); 
    exit(); 
}

// 1. Get the total number of employees (excluding Admin)
$total_emps_res = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='Employee'");
$total_emps = $total_emps_res->fetch_assoc()['count'];

// 2. Get the total number of projects
$total_projs_res = $conn->query("SELECT COUNT(*) as count FROM projects");
$total_projs = $total_projs_res->fetch_assoc()['count'];

// 3. Get 5 recently added projects (to display as a table)
$recent_projects = $conn->query("SELECT * FROM projects ORDER BY project_id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">EPM ADMIN</div>
    <a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="register_employee.php"><i class="fas fa-user-plus"></i> Register Employee</a>
    <a href="manage_projects.php"><i class="fas fa-tasks"></i> Projects</a>
    <a href="logout.php" style="color: #ff7675;"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between mb-4">
        <h2>System Overview</h2>
        <span class="badge bg-primary p-2">Logged in as Admin</span>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow p-3 mb-5 bg-white rounded border-0" style="border-left: 5px solid #4e73df !important;">
                <div class="card-body">
                    <h6 class="text-primary fw-bold text-uppercase mb-1">Total Employees</h6>
                    <div class="h2 mb-0 fw-bold text-gray-800"><?php echo $total_emps; ?></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3 mb-5 bg-white rounded border-0" style="border-left: 5px solid #1cc88a !important;">
                <div class="card-body">
                    <h6 class="text-success fw-bold text-uppercase mb-1">Total Projects</h6>
                    <div class="h2 mb-0 fw-bold text-gray-800"><?php echo $total_projs; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Projects</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Client</th>
                        <th>Start Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $recent_projects->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['project_name']; ?></td>
                        <td><?php echo $row['client_name']; ?></td>
                        <td><?php echo $row['start_date']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>