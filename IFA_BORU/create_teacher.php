<?php
/**
 * ============================================
 * CREATE TEACHER ACCOUNT - Run this once
 * ============================================
 */

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'includes/Auth.php';

try {
    // Initialize database connection
    $db = new Database();
    $pdo = $db->connect();
    
    // Teacher details
    $teacher_code = 'TCH2026001';
    $full_name = 'Dr. Sarah Smith';
    $email = 'sarah.smith@ifaboru.edu.et';
    $username = 'sarahsmith';
    $password = 'teacher123';  // Simple password
    $phone = '+251922334455';
    $hire_date = date('Y-m-d');
    
    // Check if teacher exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM teachers WHERE username = ? OR email = ?");
    $check->execute([$username, $email]);
    
    if ($check->fetchColumn() > 0) {
        // Update existing teacher
        $query = "UPDATE teachers SET 
                  full_name = ?, email = ?, phone = ?, password = ?, hire_date = ?, status = 'Active'
                  WHERE username = ? OR email = ?";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([$full_name, $email, $phone, $password, $hire_date, $username, $email]);
    } else {
        // Create new teacher
        $query = "INSERT INTO teachers 
                  (teacher_code, full_name, email, phone, username, password, hire_date, status, gender)
                  VALUES (?, ?, ?, ?, ?, ?, ?, 'Active', 'Female')";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([$teacher_code, $full_name, $email, $phone, $username, $password, $hire_date]);
    }
    
    if ($result) {
        echo "<div style='font-family: Arial; padding: 40px; text-align: center;'>";
        echo "<h1 style='color: #28a745;'>✅ Teacher Account Created!</h1>";
        echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; display: inline-block;'>";
        echo "<h3>Teacher Login Credentials:</h3>";
        echo "<p><strong>Username:</strong> sarahsmith</p>";
        echo "<p><strong>Email:</strong> sarah.smith@ifaboru.edu.et</p>";
        echo "<p><strong>Password:</strong> teacher123</p>";
        echo "</div>";
        echo "<p><a href='index.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";
        echo "<p style='color: #666; font-size: 12px;'>You can delete this file after use.</p>";
        echo "</div>";
    } else {
        echo "<h2 style='color: red;'>❌ Error creating teacher account</h2>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Database Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Make sure your database is running and configured correctly.</p>";
}
?>