<?php
/**
 * ============================================
 * ADMIN - REPORTS
 * ============================================
 */

$page_title = 'Reports';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$report_type = isset($_GET['type']) ? $_GET['type'] : 'overview';
?>

        <div class="container">
            <h1>📊 Reports & Analytics</h1>

            <div class="card" style="margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <a href="?type=overview" class="btn <?php echo ($report_type === 'overview') ? '' : 'btn-secondary'; ?>" style="text-align: center;">📈 Overview</a>
                    <a href="?type=students" class="btn <?php echo ($report_type === 'students') ? '' : 'btn-secondary'; ?>" style="text-align: center;">👨‍🎓 Students</a>
                    <a href="?type=teachers" class="btn <?php echo ($report_type === 'teachers') ? '' : 'btn-secondary'; ?>" style="text-align: center;">👨‍🏫 Teachers</a>
                    <a href="?type=classes" class="btn <?php echo ($report_type === 'classes') ? '' : 'btn-secondary'; ?>" style="text-align: center;">🎓 Classes</a>
                </div>
            </div>

            <?php if ($report_type === 'overview'): ?>
                <div class="stats">
                    <div class="stat-card">
                        <h3>Total Students</h3>
                        <div class="stat-number"><?php echo $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn(); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Active Teachers</h3>
                        <div class="stat-number"><?php echo $pdo->query("SELECT COUNT(*) FROM teachers WHERE status = 'Active'")->fetchColumn(); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Subjects</h3>
                        <div class="stat-number"><?php echo $pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn(); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Classes</h3>
                        <div class="stat-number"><?php echo $pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn(); ?></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-title">Students by Grade</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Grade</th>
                                <th>Total Students</th>
                                <th>Active Students</th>
                                <th>Inactive Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $grade_stats = $pdo->query("
                                    SELECT g.grade_level, 
                                           COUNT(s.student_id) as total,
                                           COUNT(CASE WHEN s.status = 'Active' THEN 1 END) as active,
                                           COUNT(CASE WHEN s.status = 'Inactive' THEN 1 END) as inactive
                                    FROM grades g
                                    LEFT JOIN students s ON g.grade_id = s.grade_id
                                    GROUP BY g.grade_id, g.grade_level
                                    ORDER BY g.grade_level
                                ")->fetchAll();

                                foreach ($grade_stats as $stat) {
                                    echo "<tr>
                                        <td><strong>Grade {$stat['grade_level']}</strong></td>
                                        <td>{$stat['total']}</td>
                                        <td style='color: #27ae60; font-weight: 600;'>{$stat['active']}</td>
                                        <td style='color: #e74c3c; font-weight: 600;'>{$stat['inactive']}</td>
                                    </tr>";
                                }
                            } catch (Exception $e) {
                                echo "<tr><td colspan='4' style='text-align: center; color: #e74c3c;'>Error loading grade statistics</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($report_type === 'students'): ?>
                <div class="card">
                    <div class="card-title">Student List Report</div>
                    <table style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>Student Code</th>
                                <th>Name</th>
                                <th>Grade</th>
                                <th>Section</th>
                                <th>Parent Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Enrolled Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $students = $pdo->query("
                                    SELECT s.*, g.grade_level, sec.section_name
                                    FROM students s
                                    JOIN grades g ON s.grade_id = g.grade_id
                                    JOIN sections sec ON s.section_id = sec.section_id
                                    ORDER BY s.full_name
                                ")->fetchAll();

                                if (count($students) > 0) {
                                    foreach ($students as $student) {
                                        $status_color = $student['status'] === 'Active' ? '#27ae60' : '#e74c3c';
                                        echo "<tr>
                                            <td>{$student['student_code']}</td>
                                            <td>{$student['full_name']}</td>
                                            <td>Grade {$student['grade_level']}</td>
                                            <td>Section {$student['section_name']}</td>
                                            <td>{$student['parent_phone']}</td>
                                            <td>{$student['email']}</td>
                                            <td><span style='color: {$status_color}; font-weight: 600;'>{$student['status']}</span></td>
                                            <td>{$student['enrolled_date']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' style='text-align: center; color: #999;'>No students found. <a href='add_student.php'>Add students</a></td></tr>";
                                }
                            } catch (Exception $e) {
                                echo "<tr><td colspan='8' style='text-align: center; color: #e74c3c;'>Error loading students</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($report_type === 'teachers'): ?>
                <div class="card">
                    <div class="card-title">Teacher Activity Report</div>
                    <table style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>Teacher Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Classes Assigned</th>
                                <th>Status</th>
                                <th>Hire Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $teachers = $pdo->query("
                                    SELECT t.*, 
                                           COUNT(DISTINCT c.class_id) as class_count
                                    FROM teachers t
                                    LEFT JOIN classes c ON t.teacher_id = c.teacher_id
                                    GROUP BY t.teacher_id
                                    ORDER BY t.full_name
                                ")->fetchAll();

                                if (count($teachers) > 0) {
                                    foreach ($teachers as $teacher) {
                                        $status_color = $teacher['status'] === 'Active' ? '#27ae60' : '#e74c3c';
                                        echo "<tr>
                                            <td>{$teacher['teacher_code']}</td>
                                            <td>{$teacher['full_name']}</td>
                                            <td>{$teacher['email']}</td>
                                            <td>{$teacher['class_count']}</td>
                                            <td><span style='color: {$status_color}; font-weight: 600;'>{$teacher['status']}</span></td>
                                            <td>{$teacher['hire_date']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' style='text-align: center; color: #999;'>No teachers found. <a href='add_teacher.php'>Add teachers</a></td></tr>";
                                }
                            } catch (Exception $e) {
                                echo "<tr><td colspan='6' style='text-align: center; color: #e74c3c;'>Error loading teachers</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($report_type === 'classes'): ?>
                <div class="card">
                    <div class="card-title">Classes Report</div>
                    <table style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>Grade</th>
                                <th>Section</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Academic Year</th>
                                <th>Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $classes = $pdo->query("
                                    SELECT c.*, t.full_name as teacher_name, s.subject_name, sec.section_name, g.grade_level
                                    FROM classes c
                                    JOIN teachers t ON c.teacher_id = t.teacher_id
                                    JOIN subjects s ON c.subject_id = s.subject_id
                                    JOIN sections sec ON c.section_id = sec.section_id
                                    JOIN grades g ON sec.grade_id = g.grade_id
                                    ORDER BY g.grade_level, sec.section_name, s.subject_name
                                ")->fetchAll();

                                if (count($classes) > 0) {
                                    foreach ($classes as $class) {
                                        echo "<tr>
                                            <td>Grade {$class['grade_level']}</td>
                                            <td>Section {$class['section_name']}</td>
                                            <td>{$class['subject_name']}</td>
                                            <td>{$class['teacher_name']}</td>
                                            <td>{$class['academic_year']}</td>
                                            <td>Semester {$class['semester']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' style='text-align: center; color: #999;'>No classes found. <a href='classes.php'>Create classes</a></td></tr>";
                                }
                            } catch (Exception $e) {
                                echo "<tr><td colspan='6' style='text-align: center; color: #e74c3c;'>Error loading classes</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div style="margin-top: 20px; text-align: center;">
                <button onclick="window.print()" class="btn">🖨️ Print Report</button>
            </div>
        </div>

<?php require_once 'footer.php'; ?>