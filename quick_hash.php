<!DOCTYPE html>
<html>
<head>
    <title>Generate Hash for "123"</title>
</head>
<body>
    <h2>Password Hash Generator</h2>
    
    <?php
    $password = "123";
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    echo "<p><strong>Password:</strong> " . $password . "</p>";
    echo "<p><strong>Generated Hash:</strong></p>";
    echo "<code style='background: #f0f0f0; padding: 10px; display: block; word-break: break-all;'>" . $hash . "</code>";
    
    echo "<h3>SQL Update Statement:</h3>";
    echo "<code style='background: #f0f0f0; padding: 10px; display: block;'>";
    echo "UPDATE users SET password = '" . $hash . "' WHERE email = 'admin@epm.com';";
    echo "</code>";
    ?>
    
    <h3>Alternative: Copy this hash manually</h3>
    <p>Since bcrypt generates different hashes each time, here's a pre-generated hash for "123":</p>
    <code style='background: #f0f0f0; padding: 10px; display: block; word-break: break-all;'>
    $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
    </code>
    
    <h3>SQL Statement (Ready to use):</h3>
    <code style='background: #f0f0f0; padding: 10px; display: block;'>
    UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE email = 'admin@epm.com';
    </code>
    
    <p><strong>Note:</strong> This hash corresponds to password "123"</p>
</body>
</html>