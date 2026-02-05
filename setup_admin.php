<?php
// setup_admin.php
require_once 'config/db.php';

$name = "Administrator";
$email = "admin@jhms.com";
$password = "password123";
$role = "admin";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashed_password, $role]);
    
    $user_id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("INSERT INTO profiles (user_id) VALUES (?)");
    $stmt->execute([$user_id]);

    echo "<h1>Admin Account Created!</h1>";
    echo "<p>Email: <b>$email</b></p>";
    echo "<p>Password: <b>$password</b></p>";
    echo "<p><a href='auth/login.php'>Go to Login</a></p>";
    echo "<p><i>Please delete this file after use for security reasons.</i></p>";
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>Admin account might already exist: " . $e->getMessage() . "</p>";
}
?>
