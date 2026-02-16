<?php
// Generate hash for password "123"
$password = "123";
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n\n";

echo "SQL Update Statement:\n";
echo "UPDATE users SET password = '$hash' WHERE email = 'admin@epm.com';\n";
?>