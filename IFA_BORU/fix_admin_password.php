<?php
/**
 * ============================================
 * FIX ADMIN PASSWORD - Run this once
 * ============================================
 */

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'includes/Auth.php';

try {
    // Initialize database connection
    $db = new Database();
    $pdo = $db->connect();
    
    // Hash the password properly
    $password = 'Admin123';  // Default password
    $hashed_password = Auth::hashPassword($password);
    
    // Update the admin password
    $query = "UPDATE admins SET password = ? WHERE username = 'admin'";
    $stmt = $pdo->prepare($query);
    $result = $stmt->execute([$hashed_password]);
    
    if ($result) {
        echo "<h2>✅ Admin Password Fixed Successfully!</h2>";
        echo "<p><strong>Login Credentials:</strong></p>";
        echo "<ul>";
        echo "<li><strong>Username:</strong> admin</li>";
        echo "<li><strong>Email:</strong> admin@ifaboru.edu.et</li>";
        echo "<li><strong>Password:</strong> Admin123</li>";
        echo "</ul>";
        echo "<p><a href='index.php'>Go to Login Page</a></p>";
        echo "<p><em>You can delete this file after use.</em></p>";
    } else {
        echo "<h2>❌ Error updating password</h2>";
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Database Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>