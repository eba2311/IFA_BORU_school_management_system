<?php
/**
 * ============================================
 * ADMIN - TEACHERS MANAGEMENT
 * ============================================
 */

$page_title = 'Teachers Management';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/TeacherManager.php';
require_once __DIR__ . '/../includes/Validator.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$teacherManager = new TeacherManager($pdo);
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? Validator::sanitize($_GET['search']) : '';

if ($search) {
    $teachers = $teacherManager->searchTeachers($search);
    $total = count($teachers);
} else {
    $teachers = $teacherManager->getAllTeachers($page, ITEMS_PER_PAGE);
    $total = $teacherManager->getTotalTeachers();
}

$totalPages = ceil($total / ITEMS_PER_PAGE);
?>

        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1>👨‍🏫 Teachers Management</h1>
                <a href="add_teacher.php" class="btn">➕ Add New Teacher</a>
            </div>

            <div class="card">
                <form method="GET" style="display: flex; gap: 10px;">
                    <input type="text" name="search" placeholder="Search by code, name, email, or username..." 
                           value="<?php echo $search; ?>" style="flex: 1; padding: 10px; border: 1px solid #e0e0e0; border-radius: 5px;">
                    <button type="submit" class="btn">🔍 Search</button>
                </form>
            </div>

            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Teacher Code</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Phone</th>
                            <th>Hire Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($teachers) > 0) {
                            foreach ($teachers as $teacher) {
                                $status_color = $teacher['status'] === 'Active' ? '#27ae60' : '#e74c3c';
                                echo "<tr>
                                    <td><strong>{$teacher['teacher_code']}</strong></td>
                                    <td>{$teacher['full_name']}</td>
                                    <td>{$teacher['email']}</td>
                                    <td>{$teacher['username']}</td>
                                    <td>{$teacher['phone']}</td>
                                    <td>{$teacher['hire_date']}</td>
                                    <td><span style='color: {$status_color}; font-weight: 600;'>{$teacher['status']}</span></td>
                                    <td>
                                        <a href='edit_teacher.php?id={$teacher['teacher_id']}' class='btn btn-small'>✏️ Edit</a>
                                        <a href='delete_teacher.php?id={$teacher['teacher_id']}' class='btn btn-small btn-danger' onclick='return confirm(\"Are you sure?\");'>🗑️ Delete</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align: center; color: #999;'>No teachers found. <a href='add_teacher.php'>Add your first teacher</a></td></tr>";
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