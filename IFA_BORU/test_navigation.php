<?php
/**
 * ============================================
 * NAVIGATION TEST - IFA BORU AMURU SMS
 * ============================================
 * This file tests all navigation links in the system
 */

echo "<h1>🧪 Navigation Test - IFA BORU AMURU SMS</h1>";
echo "<p>Testing all navigation links in the system...</p>";

// Test public website pages
echo "<h2>📄 Public Website Pages</h2>";
$public_pages = [
    'home.php' => 'Home Page',
    'about.php' => 'About Page', 
    'contact.php' => 'Contact Page',
    'index.php' => 'Portal Login'
];

foreach ($public_pages as $file => $name) {
    if (file_exists($file)) {
        echo "✅ <a href='$file' target='_blank'>$name</a> - File exists<br>";
    } else {
        echo "❌ $name - File missing<br>";
    }
}

// Test admin portal pages
echo "<h2>👨‍💼 Admin Portal Pages</h2>";
$admin_pages = [
    'admin/dashboard.php' => 'Admin Dashboard',
    'admin/students.php' => 'Students Management',
    'admin/teachers.php' => 'Teachers Management',
    'admin/subjects.php' => 'Subjects Management',
    'admin/classes.php' => 'Classes Management',
    'admin/rankings.php' => 'Rankings & Reports',
    'admin/reports.php' => 'Reports',
    'admin/settings.php' => 'Settings'
];

foreach ($admin_pages as $file => $name) {
    if (file_exists($file)) {
        echo "✅ <a href='$file' target='_blank'>$name</a> - File exists<br>";
    } else {
        echo "❌ $name - File missing<br>";
    }
}

// Test teacher portal pages
echo "<h2>👨‍🏫 Teacher Portal Pages</h2>";
$teacher_pages = [
    'teacher/dashboard.php' => 'Teacher Dashboard',
    'teacher/classes.php' => 'My Classes',
    'teacher/grades.php' => 'Grade Entry'
];

foreach ($teacher_pages as $file => $name) {
    if (file_exists($file)) {
        echo "✅ <a href='$file' target='_blank'>$name</a> - File exists<br>";
    } else {
        echo "❌ $name - File missing<br>";
    }
}

// Test student portal pages
echo "<h2>👨‍🎓 Student Portal Pages</h2>";
$student_pages = [
    'student/dashboard.php' => 'Student Dashboard',
    'student/profile.php' => 'My Profile',
    'student/grades.php' => 'My Grades'
];

foreach ($student_pages as $file => $name) {
    if (file_exists($file)) {
        echo "✅ <a href='$file' target='_blank'>$name</a> - File exists<br>";
    } else {
        echo "❌ $name - File missing<br>";
    }
}

// Test configuration files
echo "<h2>⚙️ Configuration Files</h2>";
$config_files = [
    'config/config.php' => 'Main Configuration',
    'config/Database.php' => 'Database Configuration',
    'includes/Auth.php' => 'Authentication Class',
    'database/schema.sql' => 'Database Schema'
];

foreach ($config_files as $file => $name) {
    if (file_exists($file)) {
        echo "✅ $name - File exists<br>";
    } else {
        echo "❌ $name - File missing<br>";
    }
}

echo "<hr>";
echo "<h2>🔗 Quick Access Links</h2>";
echo "<p><a href='home.php' target='_blank'>🏠 Visit School Website</a></p>";
echo "<p><a href='index.php' target='_blank'>🔐 Access Portal Login</a></p>";
echo "<p><a href='admin/dashboard.php' target='_blank'>👨‍💼 Admin Dashboard (requires login)</a></p>";

echo "<hr>";
echo "<h2>📋 Test Credentials</h2>";
echo "<strong>Admin Login:</strong><br>";
echo "Username: admin<br>";
echo "Password: admin123<br><br>";

echo "<strong>Teacher Login:</strong><br>";
echo "Username: teacher123<br>";
echo "Password: teacher123<br><br>";

echo "<strong>Student Login:</strong><br>";
echo "Student Code: STU001<br>";
echo "Date of Birth: 2005-01-15<br>";

echo "<hr>";
echo "<p><em>Test completed at " . date('Y-m-d H:i:s') . "</em></p>";
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    line-height: 1.6;
}

h1 {
    color: #667eea;
    border-bottom: 3px solid #667eea;
    padding-bottom: 10px;
}

h2 {
    color: #333;
    margin-top: 30px;
    margin-bottom: 15px;
}

a {
    color: #667eea;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

hr {
    margin: 30px 0;
    border: none;
    border-top: 1px solid #e0e0e0;
}
</style>