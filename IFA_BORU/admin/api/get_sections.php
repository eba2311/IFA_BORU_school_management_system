<?php
/**
 * ============================================
 * API ENDPOINT - GET SECTIONS BY GRADE
 * ============================================
 * Returns sections for a specific grade in JSON format
 */

header('Content-Type: application/json');

// Check if admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once '../../config/Database.php';
require_once '../../includes/SectionManager.php';

try {
    $db = new Database();
    $pdo = $db->connect();
    $sectionManager = new SectionManager($pdo);
    
    $grade_id = isset($_GET['grade_id']) ? (int)$_GET['grade_id'] : 0;
    
    if ($grade_id > 0) {
        $sections = $sectionManager->getSectionOptions($grade_id, true);
        echo json_encode(['success' => true, 'sections' => $sections]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid grade ID']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>