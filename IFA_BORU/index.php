<?php
/**
 * ============================================
 * MAIN LOGIN PAGE - IFA BORU AMURU SMS
 * ============================================
 */

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'includes/Auth.php';
require_once 'includes/Validator.php';

// Initialize database connection
try {
    $db = new Database();
    $pdo = $db->connect();
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Redirect if already logged in
if (Auth::isLoggedIn()) {
    if (Auth::isAdminLoggedIn()) {
        header('Location: admin/dashboard.php');
    } elseif (Auth::isTeacherLoggedIn()) {
        header('Location: teacher/dashboard.php');
    } elseif (Auth::isStudentLoggedIn()) {
        header('Location: student/dashboard.php');
    }
    exit;
}

$error = '';
$login_type = isset($_POST['login_type']) ? $_POST['login_type'] : 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new Auth($pdo);
    
    if ($login_type === 'admin') {
        $username = Validator::sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (!empty($username) && !empty($password)) {
            if ($auth->adminLogin($username, $password)) {
                header('Location: admin/dashboard.php');
                exit;
            } else {
                $error = 'Invalid username/email or password';
            }
        } else {
            $error = 'Please enter username and password';
        }
    } elseif ($login_type === 'teacher') {
        $username = Validator::sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (!empty($username) && !empty($password)) {
            if ($auth->teacherLogin($username, $password)) {
                header('Location: teacher/dashboard.php');
                exit;
            } else {
                $error = 'Invalid credentials or account is inactive';
            }
        } else {
            $error = 'Please enter username and password';
        }
    } elseif ($login_type === 'student') {
        $student_code = Validator::sanitize($_POST['student_code'] ?? '');
        $dob = Validator::sanitize($_POST['dob'] ?? '');
        
        if (!empty($student_code) && !empty($dob)) {
            if ($auth->studentLogin($student_code, $dob)) {
                header('Location: student/dashboard.php');
                exit;
            } else {
                $error = 'Invalid Student Code or Date of Birth';
            }
        } else {
            $error = 'Please enter Student Code and Date of Birth';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IFA BORU AMURU SMS</title>
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
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .login-body {
            padding: 40px;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }

        .tab-btn {
            flex: 1;
            padding: 12px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #999;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .tab-btn.active {
            color: #667eea;
            border-bottom-color: #667eea;
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
            transition: border-color 0.3s;
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
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 12px;
            margin-top: 20px;
            border-radius: 3px;
            font-size: 12px;
            color: #666;
        }

        .back-to-website {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .back-to-website a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .back-to-website a:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>📚 IFA BORU AMURU</h1>
            <p>School Management System</p>
        </div>

        <div class="login-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="tabs">
                <button class="tab-btn active" onclick="switchTab('admin')">Admin</button>
                <button class="tab-btn" onclick="switchTab('teacher')">Teacher</button>
                <button class="tab-btn" onclick="switchTab('student')">Student</button>
            </div>

            <!-- ADMIN LOGIN -->
            <div id="admin" class="tab-content active">
                <form method="POST">
                    <input type="hidden" name="login_type" value="admin">
                    
                    <div class="form-group">
                        <label for="admin_username">Username or Email</label>
                        <input type="text" id="admin_username" name="username" required placeholder="admin">
                    </div>

                    <div class="form-group">
                        <label for="admin_password">Password</label>
                        <input type="password" id="admin_password" name="password" required placeholder="password">
                    </div>

                    <button type="submit" class="btn">Login as Admin</button>

                    <div class="info-box">
                        <strong>Default Login:</strong><br>
                        Username: admin<br>
                        Password: admin123
                    </div>
                </form>
            </div>

            <!-- TEACHER LOGIN -->
            <div id="teacher" class="tab-content">
                <form method="POST">
                    <input type="hidden" name="login_type" value="teacher">
                    
                    <div class="form-group">
                        <label for="teacher_username">Username or Email</label>
                        <input type="text" id="teacher_username" name="username" required placeholder="Enter your username">
                    </div>

                    <div class="form-group">
                        <label for="teacher_password">Password</label>
                        <input type="password" id="teacher_password" name="password" required placeholder="Enter password">
                    </div>

                    <button type="submit" class="btn">Login as Teacher</button>

                    <div class="info-box">
                        <strong>Note:</strong> Teachers must be registered by the Admin first.
                    </div>
                </form>
            </div>

            <!-- STUDENT LOGIN -->
            <div id="student" class="tab-content">
                <form method="POST">
                    <input type="hidden" name="login_type" value="student">
                    
                    <div class="form-group">
                        <label for="student_code">Student Code</label>
                        <input type="text" id="student_code" name="student_code" required placeholder="e.g., STU001">
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" required>
                    </div>

                    <button type="submit" class="btn">Login as Student</button>

                    <div class="info-box">
                        <strong>Note:</strong> Use your Student Code and Date of Birth to login.
                    </div>
                </form>
            </div>

            <div class="back-to-website">
                <a href="home_basic.php">← Back to School Website</a>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            const tabs = document.querySelectorAll('.tab-content');
            const buttons = document.querySelectorAll('.tab-btn');
            
            tabs.forEach(tab => tab.classList.remove('active'));
            buttons.forEach(btn => btn.classList.remove('active'));
            
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>