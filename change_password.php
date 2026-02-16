<?php
session_start();
include 'db_connect.php'; // Database connection

// Checking if the user is logged in
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$message = "";
$status = "";

if (isset($_POST['update_pw'])) {
    $user_id = $_SESSION['user_id'];
    $old_pw = $_POST['old_pw'];
    $new_pw = $_POST['new_pw'];
    $confirm_pw = $_POST['confirm_pw'];

   // Using Prepared Statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($old_pw, $user['password'])) {
        if ($new_pw === $confirm_pw) {
            // Hashing the new password
            $hashed = password_hash($new_pw, PASSWORD_DEFAULT);
            
            // Updating the password
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $update_stmt->bind_param("si", $hashed, $user_id);
            
            if ($update_stmt->execute()) {
                $message = "// Updating the password!";
                $status = "success";
            } else {
                $message = "There is an update error.!";
                $status = "danger";
            }
        } else { 
            $message = "The two new passwords do not match.!";
            $status = "danger";
        }
    } else { 
        $message = "The old password is incorrect.!";
        $status = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 400px; border-radius: 15px;">
        <h4 class="text-center mb-4">Change Password</h4>
        <?php if($message): ?>
            <div class="alert alert-<?php echo $status; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Old Password</label>
                <input type="password" name="old_pw" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>New Password</label>
                <input type="password" name="new_pw" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Confirm new password</label>
                <input type="password" name="confirm_pw" class="form-control" required>
            </div>
            <button type="submit" name="update_pw" class="btn btn-primary w-100">Update Password</button>
            <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none">Back to Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>