<?php
/**
 * ============================================
 * CHECK ADMIN ACCOUNT - Debug Tool
 * ============================================
 */

require_once 'config/config.php';
require_once 'config/Database.php';

try {
    // Initialize database connection
    $db = new Database();
    $pdo = $db->connect();
    
    echo "<div style='font-family: Arial; padding: 20px;'>";
    echo "<h2>🔍 Admin Account Status</h2>";
    
    // Check if admins table exists
    $tables = $pdo->query("SHOW TABLES LIKE 'admins'")->fetchAll();
    if (empty($tables)) {
        echo "<p style='color: red;'>❌ Admins table does not exist!</p>";
        echo "<p>Please run the database installation first.</p>";
        echo "<p><a href='install.php'>Go to Installation</a></p>";
    } else {
        echo "<p style='color: green;'>✅ Admins table exists</p>";
        
        // Check admin accounts
        $admins = $pdo->query("SELECT * FROM admins")->fetchAll();
        
        if (empty($admins)) {
            echo "<p style='color: orange;'>⚠️ No admin accounts found</p>";
            echo "<p><strong>Solution:</strong> <a href='setup_admin.php'>Create Admin Account</a></p>";
        } else {
            echo "<p style='color: green;'>✅ Found " . count($admins) . " admin account(s)</p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
            echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Password</th><th>Full Name</th></tr>";
            
            foreach ($admins as $admin) {
                echo "<tr>";
                echo "<td>{$admin['admin_id']}</td>";
                echo "<td><strong>{$admin['username']}</strong></td>";
                echo "<td>{$admin['email']}</td>";
                echo "<td>" . substr($admin['password'], 0, 20) . "...</td>";
                echo "<td>{$admin['full_name']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Test login credentials
            echo "<h3>🧪 Test Login</h3>";
            echo "<form method='POST' style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
            echo "<p><strong>Try these credentials:</strong></p>";
            echo "<p>Username: <input type='text' name='test_username' value='admin' style='padding: 5px;'></p>";
            echo "<p>Password: <input type='text' name='test_password' value='admin123' style='padding: 5px;'></p>";
            echo "<p><button type='submit' name='test_login' style='padding: 8px 15px; background: #667eea; color: white; border: none; border-radius: 3px;'>Test Login</button></p>";
            echo "</form>";
            
            if (isset($_POST['test_login'])) {
                $test_username = $_POST['test_username'];
                $test_password = $_POST['test_password'];
                
                $query = "SELECT * FROM admins WHERE username = ? OR email = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$test_username, $test_username]);
                $admin = $stmt->fetch();
                
                if ($admin) {
                    echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
                    echo "<p>✅ Admin found: {$admin['username']}</p>";
                    echo "<p>Stored password: {$admin['password']}</p>";
                    
                    // Test password verification
                    if (password_verify($test_password, $admin['password'])) {
                        echo "<p style='color: green;'>✅ Password hash verification: SUCCESS</p>";
                    } elseif ($test_password === $admin['password']) {
                        echo "<p style='color: green;'>✅ Plain text password match: SUCCESS</p>";
                    } else {
                        echo "<p style='color: red;'>❌ Password verification: FAILED</p>";
                        echo "<p>Try password: <strong>Admin123</strong> or <strong>admin123</strong></p>";
                    }
                    echo "</div>";
                } else {
                    echo "<p style='color: red;'>❌ Admin not found with username: {$test_username}</p>";
                }
            }
        }
    }
    
    echo "<hr>";
    echo "<h3>🔧 Quick Actions</h3>";
    echo "<p><a href='setup_admin.php' style='background: #28a745; color: white; padding: 8px 15px; text-decoration: none; border-radius: 3px;'>Create/Reset Admin Account</a></p>";
    echo "<p><a href='index.php' style='background: #667eea; color: white; padding: 8px 15px; text-decoration: none; border-radius: 3px;'>Go to Login Page</a></p>";
    echo "<p><a href='install.php' style='background: #ffc107; color: black; padding: 8px 15px; text-decoration: none; border-radius: 3px;'>Run Installation</a></p>";
    
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='font-family: Arial; padding: 20px;'>";
    echo "<h2 style='color: red;'>❌ Database Error</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Possible solutions:</strong></p>";
    echo "<ul>";
    echo "<li>Make sure XAMPP MySQL is running</li>";
    echo "<li>Check database configuration in config/config.php</li>";
    echo "<li>Run the installation script: <a href='install.php'>install.php</a></li>";
    echo "</ul>";
    echo "</div>";
}
?>