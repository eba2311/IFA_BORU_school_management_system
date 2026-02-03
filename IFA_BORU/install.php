<?php
/**
 * ============================================
 * INSTALLATION SCRIPT
 * ============================================
 * 
 * Run this script once to set up the system
 * Access: http://localhost/ifa-boru-sms/install.php
 */

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if already installed
if (file_exists(__DIR__ . '/installed.lock')) {
    die("<div style='text-align: center; padding: 40px; font-family: Arial;'><h1>✅ Already Installed</h1><p>System is already installed. Please delete <code>installed.lock</code> file to reinstall.</p><a href='index.php'>Go to Login</a></div>");
}

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'includes/Auth.php';

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1) {
        // Validate database connection
        try {
            $db = new Database();
            $pdo = $db->connect();
            $_SESSION['db_connected'] = true;
            header('Location: install.php?step=2');
            exit;
        } catch (Exception $e) {
            $error = 'Database connection failed: ' . $e->getMessage();
        }
    } elseif ($step === 2) {
        // Create tables and insert data
        try {
            $db = new Database();
            $pdo = $db->connect();
            
            $schema_file = __DIR__ . '/database/schema.sql';
            if (file_exists($schema_file)) {
                $sql = file_get_contents($schema_file);
                
                // Execute SQL commands
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                foreach (explode(';', $sql) as $query) {
                    $query = trim($query);
                    if (!empty($query) && !preg_match('/^--/', $query)) {
                        try {
                            $pdo->exec($query);
                        } catch (PDOException $e) {
                            // Ignore duplicate entry errors
                            if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                                throw $e;
                            }
                        }
                    }
                }
                
                $_SESSION['db_setup'] = true;
                header('Location: install.php?step=3');
                exit;
            } else {
                $error = 'Schema file not found';
            }
        } catch (Exception $e) {
            $error = 'Database setup failed: ' . $e->getMessage();
        }
    } elseif ($step === 3) {
        // Create admin account
        $admin_username = trim($_POST['admin_username'] ?? '');
        $admin_email = trim($_POST['admin_email'] ?? '');
        $admin_password = $_POST['admin_password'] ?? '';
        $admin_password_confirm = $_POST['admin_password_confirm'] ?? '';
        $admin_name = trim($_POST['admin_name'] ?? '');
        
        if (empty($admin_username) || empty($admin_email) || empty($admin_password) || empty($admin_name)) {
            $error = 'All fields are required';
        } elseif ($admin_password !== $admin_password_confirm) {
            $error = 'Passwords do not match';
        } elseif (strlen($admin_password) < 8) {
            $error = 'Password must be at least 8 characters';
        } else {
            try {
                $db = new Database();
                $pdo = $db->connect();
                
                // Check if admin already exists
                $check_query = "SELECT COUNT(*) FROM admins WHERE username = ? OR email = ?";
                $check_stmt = $pdo->prepare($check_query);
                $check_stmt->execute([$admin_username, $admin_email]);
                $admin_exists = $check_stmt->fetchColumn() > 0;
                
                if ($admin_exists) {
                    // Update existing admin account
                    $password_hash = Auth::hashPassword($admin_password);
                    $update_query = "UPDATE admins SET email = ?, password = ?, full_name = ? WHERE username = ? OR email = ?";
                    $stmt = $pdo->prepare($update_query);
                    $stmt->execute([$admin_email, $password_hash, $admin_name, $admin_username, $admin_email]);
                } else {
                    // Create new admin account
                    $password_hash = Auth::hashPassword($admin_password);
                    $query = "INSERT INTO admins (username, email, password, full_name) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([$admin_username, $admin_email, $password_hash, $admin_name]);
                }
                
                // Create installed lock file
                file_put_contents(__DIR__ . '/installed.lock', date('Y-m-d H:i:s'));
                
                $_SESSION['installation_complete'] = true;
                $_SESSION['admin_username'] = $admin_username;
                $_SESSION['admin_email'] = $admin_email;
                header('Location: install.php?step=4');
                exit;
            } catch (Exception $e) {
                $error = 'Error creating admin account: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFA BORU SMS - Installation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .installation-container {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .install-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .install-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .install-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .install-body {
            padding: 40px;
        }
        
        .progress-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .progress-step {
            flex: 1;
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
        }
        
        .progress-step.active {
            background: #667eea;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .step-content {
            display: none;
        }
        
        .step-content.active {
            display: block;
        }
        
        .step-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }
        
        .step-info {
            background: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 3px;
            font-size: 14px;
            color: #666;
        }
        
        .success-box {
            text-align: center;
            padding: 40px 20px;
        }
        
        .success-box h2 {
            color: #27ae60;
            margin-bottom: 15px;
            font-size: 32px;
        }
        
        .success-box p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .login-creds {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 13px;
            text-align: left;
        }
        
        .login-creds strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="installation-container">
        <div class="install-header">
            <h1>📚 IFA BORU AMURU SMS</h1>
            <p>Installation Wizard</p>
        </div>
        
        <div class="install-body">
            <div class="progress-bar">
                <div class="progress-step <?php echo ($step >= 1) ? 'active' : ''; ?>"></div>
                <div class="progress-step <?php echo ($step >= 2) ? 'active' : ''; ?>"></div>
                <div class="progress-step <?php echo ($step >= 3) ? 'active' : ''; ?>"></div>
                <div class="progress-step <?php echo ($step >= 4) ? 'active' : ''; ?>"></div>
            </div>
            
            <!-- Step 1: Database Connection -->
            <div class="step-content <?php echo ($step === 1) ? 'active' : ''; ?>">
                <div class="step-title">Step 1: Database Connection</div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="step-info">
                    <strong>Checking database configuration...</strong><br>
                    Make sure your database credentials are correctly set in <code>config/config.php</code>
                </div>
                
                <form method="POST">
                    <button type="submit" class="btn">✅ Test Connection & Continue</button>
                </form>
            </div>
            
            <!-- Step 2: Database Setup -->
            <div class="step-content <?php echo ($step === 2) ? 'active' : ''; ?>">
                <div class="step-title">Step 2: Setting Up Database</div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="step-info">
                    <strong>Creating database tables...</strong><br>
                    This will create all necessary tables, subjects, grades, and sections.
                </div>
                
                <form method="POST">
                    <button type="submit" class="btn">🔧 Create Tables & Continue</button>
                </form>
            </div>
            
            <!-- Step 3: Admin Account -->
            <div class="step-content <?php echo ($step === 3) ? 'active' : ''; ?>">
                <div class="step-title">Step 3: Create Admin Account</div>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="step-info">
                    <strong>Create the administrator account</strong><br>
                    This account will have full access to the system. If an admin account already exists, it will be updated with your new credentials.
                </div>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="admin_name">Full Name *</label>
                        <input type="text" id="admin_name" name="admin_name" required placeholder="System Administrator">
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_username">Username *</label>
                        <input type="text" id="admin_username" name="admin_username" required placeholder="admin">
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_email">Email *</label>
                        <input type="email" id="admin_email" name="admin_email" required placeholder="admin@ifaboru.edu.et">
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_password">Password *</label>
                        <input type="password" id="admin_password" name="admin_password" required placeholder="Strong password (8+ chars)">
                    </div>
                    
                    <div class="form-group">
                        <label for="admin_password_confirm">Confirm Password *</label>
                        <input type="password" id="admin_password_confirm" name="admin_password_confirm" required placeholder="Confirm password">
                    </div>
                    
                    <button type="submit" class="btn">✅ Create Admin Account & Complete</button>
                </form>
            </div>
            
            <!-- Step 4: Completion -->
            <div class="step-content <?php echo ($step === 4) ? 'active' : ''; ?>">
                <div class="success-box">
                    <h2>✅ Installation Complete!</h2>
                    
                    <div class="alert alert-success">
                        System successfully installed and ready to use.
                    </div>
                    
                    <p><strong>Next Steps:</strong></p>
                    <ul style="text-align: left; display: inline-block;">
                        <li>✅ Login to Admin panel</li>
                        <li>✅ Add teachers and students</li>
                        <li>✅ Configure system settings</li>
                        <li>✅ Create classes and assign teachers</li>
                        <li>✅ Manage grades</li>
                    </ul>
                    
                    <div class="login-creds">
                        <strong>Admin Login Credentials:</strong><br><br>
                        Username: <?php echo isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'admin'; ?><br>
                        Email: <?php echo isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : 'admin@ifaboru.edu.et'; ?><br>
                        Password: (as you set during installation)
                    </div>
                    
                    <a href="index.php" class="btn" style="text-decoration: none; display: inline-block; margin-top: 20px;">🚀 Go to Login Page</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
