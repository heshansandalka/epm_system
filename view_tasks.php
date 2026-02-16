<?php
session_start();
include 'db_connect.php';

// Verifying that you are an employee
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve the projects assigned to the employee from the database
$query = "SELECT p.project_name, p.client_name, pa.role_in_project, p.start_date, p.status 
          FROM project_assignments pa
          JOIN projects p ON pa.project_id = p.project_id
          WHERE pa.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$tasks = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <title>My Tasks - EPM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="sidebar" style="background: linear-gradient(180deg, #1cc88a 0%, #13855c 100%);">
    <div class="sidebar-header">EPM WORKER</div>
    <a href="employee_dashboard.php"><i class="fas fa-home"></i> My Dashboard</a>
    <a href="view_tasks.php" class="active"><i class="fas fa-list-check"></i> My Tasks</a>
    <a href="submit_log.php"><i class="fas fa-edit"></i> Submit Work Log</a>
    <a href="logout.php" style="color: #ff7675;"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-tasks text-success"></i> My Assigned Tasks</h2>
            <a href="employee_dashboard.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Project Name</th>
                            <th>Client</th>
                            <th>My Role</th>
                            <th>Start Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($tasks->num_rows > 0): ?>
                            <?php while($row = $tasks->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?php echo $row['project_name']; ?></td>
                                <td><?php echo $row['client_name']; ?></td>
                                <td><span class="badge bg-info text-dark"><?php echo $row['role_in_project']; ?></span></td>
                                <td><?php echo $row['start_date']; ?></td>
                                <td>
                                    <?php 
                                    $status_class = ($row['status'] == 'Active') ? 'success' : 'warning';
                                    echo "<span class='badge bg-$status_class'>".$row['status']."</span>";
                                    ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    You have not been assigned any projects yet..
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>