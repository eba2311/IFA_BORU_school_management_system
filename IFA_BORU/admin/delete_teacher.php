<?php
/**
 * ============================================
 * DELETE TEACHER
 * ============================================
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/TeacherManager.php';
require_once __DIR__ . '/../includes/Auth.php';

if (!Auth::isAdminLoggedIn()) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$teacher_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($teacher_id > 0) {
    $teacherManager = new TeacherManager($pdo);
    $teacherManager->deleteTeacher($teacher_id);
}

header('Location: teachers.php');
exit;
?>
