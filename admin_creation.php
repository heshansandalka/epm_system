<?php
include 'db_connect.php'; // Connecting to the database

// Admin details
$full_name = "System Admin";
$email = "admin@gmail.com";
$password = "admin";// Change this to a password of your choice
$role = "Admin";

// Securely hashing the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into database (using Prepared Statement)
$stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);

if ($stmt->execute()) {
    echo "<h2>Admin successfully created!</h2>";
    echo "<p>Email: $email</p>";
    echo "<p>Password: $password</p>";
    echo "<br><a href='index.php'>Login now</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>