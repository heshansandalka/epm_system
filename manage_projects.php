<?php
session_start();
include 'db_connect.php';

// Checking if you are an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

$message = "";

// 1. Adding a new project
if (isset($_POST['add_project'])) {
    $stmt = $conn->prepare("INSERT INTO projects (project_name, client_name, start_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['p_name'], $_POST['c_name'], $_POST['s_date']);
    if($stmt->execute()) { $message = "ව්‍යාපෘතිය සාර්ථකව එක් කරන ලදී!"; }
}

// 2.  (Assign Employee logic)
if (isset($_POST['assign_emp'])) {
    $p_id = $_POST['project_id'];
    $u_id = $_POST['user_id'];
    $r_name = $_POST['role_name'];

    $stmt = $conn->prepare("INSERT INTO project_assignments (project_id, user_id, role_in_project) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $p_id, $u_id, $r_name);
    
    if($stmt->execute()) { 
        $message = "Employee was successfully assigned.!"; 
    } else {
        $message = "An error occurred.: " . $conn->error;
    }
}

// 3. Deleting a project (Delete)
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM projects WHERE project_id = $id");
    header("Location: manage_projects.php");
}

// Get the project list
$projects = $conn->query("SELECT * FROM projects");
?>

<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <title>Manage Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="sidebar">
    <div class="sidebar-header">EPM ADMIN</div>
    <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="register_employee.php"><i class="fas fa-user-plus"></i> Register Employee</a>
    <a href="manage_projects.php" class="active"><i class="fas fa-tasks"></i> Projects</a>
    <a href="logout.php" style="color: #ff7675;"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Manage Projects & Assignments</h3>
            <a href="admin_dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-5">
                <div class="card shadow p-4 mb-4">
                    <h4>Add New Project</h4>
                    <form method="POST">
                        <input type="text" name="p_name" class="form-control mb-2" placeholder="Project Name" required>
                        <input type="text" name="c_name" class="form-control mb-2" placeholder="Client Name" required>
                        <input type="date" name="s_date" class="form-control mb-2" required>
                        <button type="submit" name="add_project" class="btn btn-success w-100">Create Project</button>
                    </form>
                </div>

                <div class="card shadow p-4">
                    <h4>Assign Employee</h4>
                    <form method="POST">
                        <label class="small">Select project:</label>
                        <select name="project_id" class="form-select mb-2" required>
                            <option value="">Select Project</option>
                            <?php 
                            $p_list = $conn->query("SELECT * FROM projects");
                            while($r = $p_list->fetch_assoc()) echo "<option value='".$r['project_id']."'>".$r['project_name']."</option>"; 
                            ?>
                        </select>
                        
                        <label class="small">Select the employee:</label>
                        <select name="user_id" class="form-select mb-2" required>
                            <option value="">Select Employee</option>
                            <?php 
                            $u_list = $conn->query("SELECT * FROM users WHERE role='Employee'");
                            while($r = $u_list->fetch_assoc()) echo "<option value='".$r['user_id']."'>".$r['full_name']."</option>"; 
                            ?>
                        </select>
                        
                        <input type="text" name="role_name" class="form-control mb-2" placeholder="Role (e.g. Developer)" required>
                        <button type="submit" name="assign_emp" class="btn btn-primary w-100">Assign Now</button>
                    </form>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow p-4">
                    <h4>Current Projects</h4>
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Client</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($projects->num_rows > 0): ?>
                                <?php while($row = $projects->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['project_name']; ?></td>
                                    <td><?php echo $row['client_name']; ?></td>
                                    <td>
                                        <a href="manage_projects.php?delete_id=<?php echo $row['project_id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this??')">
                                           <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center">There are no projects in the system..</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>