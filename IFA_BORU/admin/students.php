<?php
/**
 * ============================================
 * ADMIN - STUDENTS LIST & MANAGEMENT
 * ============================================
 */

$page_title = 'Students Management';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/StudentManager.php';
require_once __DIR__ . '/../includes/SectionManager.php';
require_once __DIR__ . '/../includes/Validator.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$studentManager = new StudentManager($pdo);
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? Validator::sanitize($_GET['search']) : '';

if ($search) {
    $students = $studentManager->searchStudents($search);
    $total = count($students);
} else {
    $students = $studentManager->getAllStudents($page, ITEMS_PER_PAGE);
    $total = $studentManager->getTotalStudents();
}

$totalPages = ceil($total / ITEMS_PER_PAGE);
?>

        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1>👨‍🎓 Students Management</h1>
                <a href="add_student.php" class="btn">➕ Add New Student</a>
            </div>

            <div class="card">
                <form method="GET" style="display: flex; gap: 10px;">
                    <input type="text" name="search" placeholder="Search by code, name, or email..." 
                           value="<?php echo $search; ?>" style="flex: 1; padding: 10px; border: 1px solid #e0e0e0; border-radius: 5px;">
                    <button type="submit" class="btn">🔍 Search</button>
                </form>
            </div>

            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Student Code</th>
                            <th>Full Name</th>
                            <th>Date of Birth</th>
                            <th>Grade</th>
                            <th>Section</th>
                            <th>Parent Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($students) > 0) {
                            foreach ($students as $student) {
                                $status_color = $student['status'] === 'Active' ? '#27ae60' : '#e74c3c';
                                echo "<tr>
                                    <td><strong>{$student['student_code']}</strong></td>
                                    <td>{$student['full_name']}</td>
                                    <td>{$student['date_of_birth']}</td>
                                    <td>Grade {$student['grade_level']}</td>
                                    <td>Section {$student['section_name']}</td>
                                    <td>{$student['parent_phone']}</td>
                                    <td><span style='color: {$status_color}; font-weight: 600;'>{$student['status']}</span></td>
                                    <td>
                                        <a href='edit_student.php?id={$student['student_id']}' class='btn btn-small'>✏️ Edit</a>
                                        <a href='delete_student.php?id={$student['student_id']}' class='btn btn-small btn-danger' onclick='return confirm(\"Are you sure?\");'>🗑️ Delete</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align: center; color: #999;'>No students found. <a href='add_student.php'>Add your first student</a></td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if (!$search && $totalPages > 1): ?>
            <div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" 
                       style="padding: 8px 12px; border: 1px solid #e0e0e0; border-radius: 5px; text-decoration: none; 
                               background: <?php echo ($i === $page) ? '#667eea' : 'white'; ?>; 
                               color: <?php echo ($i === $page) ? 'white' : '#333'; ?>;">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>

<?php require_once 'footer.php'; ?>