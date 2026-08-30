<?php
require 'config/database.php';

// This is your simple password
$my_password = '123456'; 

// This code automatically updates the database
$hash = password_hash($my_password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE clients SET password = ?");
$stmt->execute([$hash]);

echo "<h2 style='color:green;'>✅ Success!</h2>";
echo "<p>Your password is now set to: <b>123456</b></p>";
echo "<p><a href='client/login.php'>Go to Login</a></p>";
?>