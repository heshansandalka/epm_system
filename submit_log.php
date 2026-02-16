<?php
session_start();
include 'db_connect.php';

// Verifying that you are an employee
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Entering the work report
if (isset($_POST['submit_log'])) {
    $p_id = $_POST['project_id'];
    $task = $_POST['task_desc'];
    $hours = $_POST['hours'];
    $date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO work_logs (user_id, project_id, log_date, task_description, hours_spent) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissd", $user_id, $p_id, $date, $task, $hours);
    
    if($stmt->execute()) {
        $message = "වැඩ වාර්තාව සාර්ථකව සුරැකින!";
    }
}
?>

<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <title>Submit Work Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow p-4">
                    <div class="d-flex justify-content-between mb-4">
                        <h4>Daily Work Log</h4>
                        <a href="employee_dashboard.php" class="btn btn-outline-secondary btn-sm">Back</a>
                    </div>

                    <?php if($message): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Select project</label>
                            <select name="project_id" class="form-select" required>
                                <option value="">Select Project</option>
                                <?php 
                               // Show only projects relevant to the employee
                                $projs = $conn->query("SELECT p.project_id, p.project_name FROM projects p 
                                                      JOIN project_assignments pa ON p.project_id = pa.project_id 
                                                      WHERE pa.user_id = $user_id");
                                while($row = $projs->fetch_assoc()) {
                                    echo "<option value='".$row['project_id']."'>".$row['project_name']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Today's work</label>
                            <textarea name="task_desc" class="form-control" rows="4" placeholder="For example: Fixing validation on the login page..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Elapsed time (hours)</label>
                            <input type="number" step="0.5" name="hours" class="form-control" placeholder="Ex: 2.5" required>
                        </div>

                        <button type="submit" name="submit_log" class="btn btn-success w-100">Submit Log</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>