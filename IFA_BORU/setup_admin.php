<?php
/**
 * ============================================
 * SETUP ADMIN ACCOUNT - Run this once
 * ============================================
 */

require_once 'config/config.php';
require_once 'config/Database.php';

try {
    // Initialize database connection
    $db = new Database();
    $pdo = $db->connect();
    
    // Create or update admin account with simple password
    $username = 'admin';
    $email = 'admin@ifaboru.edu.et';
    $password = 'admin123';  // Simple password
    $full_name = 'System Administrator';
    $phone = '+251911223344';
    
    // Check if admin exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ?");
    $check->execute([$username]);
    
    if ($check->fetchColumn() > 0) {
        // Update existing admin
        $query = "UPDATE admins SET email = ?, password = ?, full_name = ?, phone = ? WHERE username = ?";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([$email, $password, $full_name, $phone, $username]);
    } else {
        // Create new admin
        $query = "INSERT INTO admins (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([$username, $email, $password, $full_name, $phone]);
    }
    
    if ($result) {
        echo "<div style='font-family: Arial; padding: 40px; text-align: center;'>";
        echo "<h1 style='color: #28a745;'>✅ Admin Account Ready!</h1>";
        echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; display: inline-block;'>";
        echo "<h3>Login Credentials:</h3>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Email:</strong> admin@ifaboru.edu.et</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "</div>";
        echo "<p><a href='index.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";
        echo "<p style='color: #666; font-size: 12px;'>You can delete this file after use.</p>";
        echo "</div>";
    } else {
        echo "<h2 style='color: red;'>❌ Error setting up admin account</h2>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Database Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Make sure your database is running and configured correctly.</p>";
}
?>