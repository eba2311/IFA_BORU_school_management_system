<?php
/**
 * ============================================
 * GET SECTIONS BY GRADE (AJAX ENDPOINT)
 * ============================================
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/Auth.php';

// Check if admin is logged in
if (!Auth::isAdminLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if grade_id is provided
if (!isset($_GET['grade_id']) || empty($_GET['grade_id'])) {
    echo json_encode([]);
    exit;
}

$grade_id = (int)$_GET['grade_id'];

try {
    // Initialize database connection
    $db = new Database();
    $pdo = $db->connect();
    
    // Get sections for the specified grade
    $query = "SELECT section_id, section_name FROM sections WHERE grade_id = ? ORDER BY section_name";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$grade_id]);
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($sections);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>