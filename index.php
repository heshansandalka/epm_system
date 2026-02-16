<?php
session_start();
include 'db_connect.php'; //

//Redirecting to the Dashboard if the user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'Admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: employee_dashboard.php");
    }
    exit();
}

$error = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE email = ?"); //
    $stmt->bind_param("s", $email); //
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) { //
            
            // Renewing the Session ID for security
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id']; //
            $_SESSION['role'] = $user['role']; //

            if ($user['role'] === 'Admin') {
                header("Location: admin_dashboard.php"); //
            } else {
                header("Location: employee_dashboard.php"); //
            }
            exit();
        } else {
            $error = "Incorrect email or password.!"; //The most secure message
        }
    } else {
        $error = "Incorrect email or password.!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EPM System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card h2 { color: #333; margin-bottom: 25px; }
        .error-msg { color: #e74c3c; background: #fadbd8; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9rem; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #4e73df; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; }
        button:hover { background: #224abe; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>EPM Login</h2>
    
    <?php if($error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login to System</button>
    </form>
    
    <p style="margin-top: 20px; font-size: 0.8rem; color: #888;">&copy; 2026 EPM Project Management</p>
</div>

</body>
</html>