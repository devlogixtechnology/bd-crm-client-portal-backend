<?php
require 'config/database.php';

// 1. Agar 'role' column nahi hai to usay add karein
try {
    $pdo->exec("ALTER TABLE clients ADD COLUMN role ENUM('admin', 'client') DEFAULT 'client' AFTER password");
} catch (PDOException $e) {
    // Column pehle se मौजूद hai, koi baat nahi
}

// 2. Admin ka email aur password set karein
$email = 'admin@example.com';
$password = '123456'; // Yahan apna password likhein
$hash = password_hash($password, PASSWORD_DEFAULT);

// 3. Database mein update karein
$stmt = $pdo->prepare("UPDATE clients SET password = ?, role = 'admin' WHERE email = ?");
$stmt->execute([$hash, $email]);

if ($stmt->rowCount() > 0) {
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
    echo "<h2 style='color:green;'>✅ Admin Account Successfully Fixed!</h2>";
    echo "<p>Ab aap in details se Admin Login kar sakte hain:</p>";
    echo "<p><strong>Email:</strong> admin@example.com</p>";
    echo "<p><strong>Password:</strong> 123456</p>";
    echo "<p><strong>Role:</strong> Admin</p>";
    echo "<br>";
    echo "<a href='admin/login.php' style='background:#2563eb; color:white; padding:12px 25px; text-decoration:none; border-radius:5px; font-weight:bold;'>Go to Admin Login</a>";
    echo "</div>";
} else {
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
    echo "<h2 style='color:red;'>❌ Error: User not found!</h2>";
    echo "<p>Database mein 'admin@example.com' email nahi hai. Pehle client se signup karein.</p>";
    echo "</div>";
}
?>