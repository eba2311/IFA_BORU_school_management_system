<?php
/**
 * ============================================
 * CREATE STUDENT ACCOUNT - Run this once
 * ============================================
 */

require_once 'config/config.php';
require_once 'config/Database.php';

try {
    // Initialize database connection
    $db = new Database();
    $pdo = $db->connect();
    
    // Student details
    $student_code = 'STU20260001';
    $full_name = 'John Doe';
    $date_of_birth = '2005-01-15';
    $gender = 'Male';
    $grade_id = 1; // Grade 9
    $section_id = 1; // Section A
    $parent_name = 'Jane Doe';
    $parent_phone = '+251911223344';
    $address = 'Addis Ababa, Ethiopia';
    $email = 'john.doe@student.ifaboru.edu.et';
    $phone = '+251922334455';
    $enrolled_date = date('Y-m-d');
    
    // Check if student exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM students WHERE student_code = ? OR email = ?");
    $check->execute([$student_code, $email]);
    
    if ($check->fetchColumn() > 0) {
        // Update existing student
        $query = "UPDATE students SET 
                  full_name = ?, date_of_birth = ?, gender = ?, grade_id = ?, section_id = ?,
                  parent_name = ?, parent_phone = ?, address = ?, email = ?, phone = ?, 
                  enrolled_date = ?, status = 'Active'
                  WHERE student_code = ? OR email = ?";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([
            $full_name, $date_of_birth, $gender, $grade_id, $section_id,
            $parent_name, $parent_phone, $address, $email, $phone, 
            $enrolled_date, $student_code, $email
        ]);
    } else {
        // Create new student
        $query = "INSERT INTO students 
                  (student_code, full_name, date_of_birth, gender, grade_id, section_id,
                   parent_name, parent_phone, address, email, phone, enrolled_date, status)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')";
        $stmt = $pdo->prepare($query);
        $result = $stmt->execute([
            $student_code, $full_name, $date_of_birth, $gender, $grade_id, $section_id,
            $parent_name, $parent_phone, $address, $email, $phone, $enrolled_date
        ]);
    }
    
    if ($result) {
        echo "<div style='font-family: Arial; padding: 40px; text-align: center;'>";
        echo "<h1 style='color: #17a2b8;'>✅ Student Account Created!</h1>";
        echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; display: inline-block;'>";
        echo "<h3>Student Login Credentials:</h3>";
        echo "<p><strong>Student Code:</strong> STU20260001</p>";
        echo "<p><strong>Date of Birth:</strong> 2005-01-15</p>";
        echo "<p><em>Use Student Code and Date of Birth to login</em></p>";
        echo "</div>";
        echo "<p><a href='index.php' style='background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a></p>";
        echo "<p style='color: #666; font-size: 12px;'>You can delete this file after use.</p>";
        echo "</div>";
    } else {
        echo "<h2 style='color: red;'>❌ Error creating student account</h2>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Database Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Make sure your database is running and configured correctly.</p>";
}
?>