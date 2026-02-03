<?php
/**
 * ============================================
 * STUDENT HEADER & NAVIGATION
 * ============================================
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/Auth.php';

if (!Auth::isStudentLoggedIn()) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . APP_NAME : APP_NAME; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .navbar {
            background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
            color: white;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 20px;
            font-weight: bold;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .navbar-menu a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .user-menu .student-name {
            color: white;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .user-menu .student-name:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            min-width: 200px;
            display: none;
            z-index: 1000;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-dropdown a {
            display: block;
            padding: 10px 15px;
            color: #333 !important;
            text-decoration: none;
            border-bottom: 1px solid #f0f0f0;
        }

        .user-dropdown a:hover {
            background: #f8f9fa;
        }

        .user-dropdown a:last-child {
            border-bottom: none;
        }

        .sidebar {
            width: 250px;
            background: white;
            height: calc(100vh - 60px);
            border-right: 1px solid #e0e0e0;
            overflow-y: auto;
            position: fixed;
            left: 0;
            top: 60px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            border-bottom: 1px solid #f0f0f0;
        }

        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #f0f0f0;
            color: #17a2b8;
            border-left: 4px solid #17a2b8;
            padding-left: 16px;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #17a2b8;
        }

        .stat-card h3 {
            color: #999;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th {
            background: #f0f0f0;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        table tr:hover {
            background: #f9f9f9;
        }

        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
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

        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .profile-info div {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .profile-info strong {
            color: #17a2b8;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1000;
            }

            .main-content {
                margin-left: 0;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .profile-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">👨‍🎓 IFA BORU AMURU - Student Portal</div>
        <div class="navbar-menu">
            <a href="dashboard.php">🏠 Home</a>
            <a href="dashboard.php">Dashboard</a>
            <div class="user-menu">
                <span class="student-name" onclick="toggleUserDropdown()">
                    👨‍🎓 <?php echo $_SESSION['student_name']; ?> ▼
                </span>
                <div class="user-dropdown" id="userDropdown">
                    <a href="dashboard.php">📊 Dashboard</a>
                    <a href="profile.php">👤 My Profile</a>
                    <a href="grades.php">📝 My Grades</a>
                    <hr style="margin: 5px 0; border: none; border-top: 1px solid #f0f0f0;">
                    <a href="logout.php">🚪 Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) === 'dashboard.php') ? 'active' : ''; ?>">🏠 Home</a></li>
            <li><a href="dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) === 'dashboard.php') ? 'active' : ''; ?>">📊 Dashboard</a></li>
            <li><a href="profile.php" class="<?php echo (basename($_SERVER['PHP_SELF']) === 'profile.php') ? 'active' : ''; ?>">👤 My Profile</a></li>
            <li><a href="grades.php" class="<?php echo (basename($_SERVER['PHP_SELF']) === 'grades.php') ? 'active' : ''; ?>">📝 My Grades</a></li>
        </ul>
    </aside>

    <div class="main-content">

<script>
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    const dropdown = document.getElementById('userDropdown');
    
    if (!userMenu.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});
</script>