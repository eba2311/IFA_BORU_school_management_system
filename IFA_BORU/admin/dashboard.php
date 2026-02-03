<?php
/**
 * ============================================
 * ADMIN DASHBOARD
 * ============================================
 */

$page_title = 'Dashboard';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/SectionManager.php';

// Initialize database connection
try {
    $db = new Database();
    $pdo = $db->connect();
} catch (Exception $e) {
    die('Database connection failed');
}

// Get statistics
try {
    $students_count = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $teachers_count = $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
    $subjects_count = $pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();
    $classes_count = $pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();
    $active_students = $pdo->query("SELECT COUNT(*) FROM students WHERE status = 'Active'")->fetchColumn();
    $sections_count = $pdo->query("SELECT COUNT(*) FROM sections")->fetchColumn();
    
    // Get section statistics
    $sectionManager = new SectionManager($pdo);
    $section_stats = $pdo->query("
        SELECT 
            g.grade_level,
            COUNT(s.section_id) as section_count,
            SUM(CASE WHEN st.student_id IS NOT NULL THEN 1 ELSE 0 END) as total_students,
            SUM(s.max_students) as total_capacity
        FROM grades g
        LEFT JOIN sections s ON g.grade_id = s.grade_id
        LEFT JOIN students st ON s.section_id = st.section_id AND st.status = 'Active'
        GROUP BY g.grade_id, g.grade_level
        ORDER BY g.grade_level
    ")->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $students_count = $teachers_count = $subjects_count = $classes_count = $active_students = $sections_count = 0;
    $section_stats = [];
}
?>

        <div class="container">
            <h1>Welcome, <?php echo $_SESSION['admin_name'] ?? 'Administrator'; ?>! 👋</h1>
            <p style="color: #999; margin-bottom: 30px;">Here's an overview of your school management system</p>

            <div class="stats">
                <div class="stat-card" style="border-left-color: #667eea;">
                    <h3>Total Students</h3>
                    <div class="stat-number"><?php echo $students_count; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #f39c12;">
                    <h3>Active Students</h3>
                    <div class="stat-number"><?php echo $active_students; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #27ae60;">
                    <h3>Total Teachers</h3>
                    <div class="stat-number"><?php echo $teachers_count; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #e74c3c;">
                    <h3>Total Subjects</h3>
                    <div class="stat-number"><?php echo $subjects_count; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #9b59b6;">
                    <h3>Total Sections</h3>
                    <div class="stat-number"><?php echo $sections_count; ?></div>
                </div>
            </div>

            <!-- Section Statistics -->
            <div class="card">
                <div class="card-title">📋 Section Overview</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <?php foreach ($section_stats as $stat): ?>
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea;">
                            <h4 style="margin-bottom: 10px; color: #333;">Grade <?php echo $stat['grade_level']; ?></h4>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span>Sections:</span>
                                <strong><?php echo $stat['section_count']; ?></strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span>Students:</span>
                                <strong><?php echo $stat['total_students']; ?></strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span>Capacity:</span>
                                <strong><?php echo $stat['total_capacity']; ?></strong>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Available:</span>
                                <strong style="color: #27ae60;"><?php echo $stat['total_capacity'] - $stat['total_students']; ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="manage_sections.php" class="btn">📋 Manage Sections</a>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Quick Actions</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">
                    <a href="add_student.php" class="btn">➕ Add Student</a>
                    <a href="add_teacher.php" class="btn">➕ Add Teacher</a>
                    <a href="subjects.php" class="btn">➕ Add Subject</a>
                    <a href="classes.php" class="btn">➕ Create Class</a>
                    <a href="reports.php" class="btn btn-secondary">📊 View Reports</a>
                    <a href="settings.php" class="btn btn-secondary">⚙️ Settings</a>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Recent Students</div>
                <table>
                    <thead>
                        <tr>
                            <th>Student Code</th>
                            <th>Full Name</th>
                            <th>Grade</th>
                            <th>Section</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $query = "SELECT s.student_code, s.full_name, g.grade_level, sec.section_name, s.status 
                                      FROM students s
                                      JOIN grades g ON s.grade_id = g.grade_id
                                      JOIN sections sec ON s.section_id = sec.section_id
                                      ORDER BY s.created_at DESC LIMIT 5";
                            $stmt = $pdo->query($query);
                            $students = $stmt->fetchAll();
                            
                            if (count($students) > 0) {
                                foreach ($students as $student) {
                                    $status_color = $student['status'] === 'Active' ? '#27ae60' : '#e74c3c';
                                    echo "<tr>
                                        <td><strong>{$student['student_code']}</strong></td>
                                        <td>{$student['full_name']}</td>
                                        <td>Grade {$student['grade_level']}</td>
                                        <td>Section {$student['section_name']}</td>
                                        <td><span style='color: {$status_color}; font-weight: 600;'>{$student['status']}</span></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center; color: #999;'>No students yet. <a href='add_student.php'>Add your first student</a></td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='5' style='text-align: center; color: #e74c3c;'>Error loading students</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-title">System Status</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                        <div style="font-size: 24px; color: #28a745;">✅</div>
                        <div style="font-size: 12px; color: #666; margin-top: 5px;">Database Connected</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                        <div style="font-size: 24px; color: #28a745;">✅</div>
                        <div style="font-size: 12px; color: #666; margin-top: 5px;">System Online</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                        <div style="font-size: 24px; color: #17a2b8;">📊</div>
                        <div style="font-size: 12px; color: #666; margin-top: 5px;">Reports Ready</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                        <div style="font-size: 24px; color: #ffc107;">⚙️</div>
                        <div style="font-size: 12px; color: #666; margin-top: 5px;">Settings Available</div>
                    </div>
                </div>
            </div>
        </div>

<?php require_once 'footer.php'; ?>