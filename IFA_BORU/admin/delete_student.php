<?php
/**
 * ============================================
 * DELETE STUDENT
 * ============================================
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/StudentManager.php';
require_once __DIR__ . '/../includes/Auth.php';

if (!Auth::isAdminLoggedIn()) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($student_id > 0) {
    $studentManager = new StudentManager($pdo);
    $studentManager->deleteStudent($student_id);
}

header('Location: students.php');
exit;
?>
