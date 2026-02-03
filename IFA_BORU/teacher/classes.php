<?php
/**
 * ============================================
 * TEACHER - MY CLASSES
 * ============================================
 */

$page_title = 'My Classes';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$teacher_id = $_SESSION['teacher_id'];
?>

        <div class="container">
            <h1>🎓 My Classes</h1>
            <p style="color: #999; margin-bottom: 30px;">Classes assigned to you</p>

            <div class="card">
                <div class="card-title">Assigned Classes</div>
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Grade</th>
                            <th>Section</th>
                            <th>Students</th>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $query = "SELECT c.*, s.subject_name, sec.section_name, g.grade_level,
                                             COUNT(st.student_id) as student_count
                                      FROM classes c
                                      JOIN subjects s ON c.subject_id = s.subject_id
                                      JOIN sections sec ON c.section_id = sec.section_id
                                      JOIN grades g ON sec.grade_id = g.grade_id
                                      LEFT JOIN students st ON sec.section_id = st.section_id AND st.status = 'Active'
                                      WHERE c.teacher_id = ?
                                      GROUP BY c.class_id
                                      ORDER BY g.grade_level, sec.section_name, s.subject_name";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute([$teacher_id]);
                            $classes = $stmt->fetchAll();
                            
                            if (count($classes) > 0) {
                                foreach ($classes as $class) {
                                    echo "<tr>
                                        <td><strong>{$class['subject_name']}</strong></td>
                                        <td>Grade {$class['grade_level']}</td>
                                        <td>Section {$class['section_name']}</td>
                                        <td>{$class['student_count']} students</td>
                                        <td>{$class['academic_year']}</td>
                                        <td>Semester {$class['semester']}</td>
                                        <td>
                                            <a href='grades.php?class_id={$class['class_id']}' class='btn btn-small'>📝 Enter Grades</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align: center; color: #999;'>No classes assigned yet. Contact admin to assign classes.</td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='7' style='text-align: center; color: #e74c3c;'>Error loading classes</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php if (count($classes ?? []) > 0): ?>
            <div class="card">
                <div class="card-title">Students in My Classes</div>
                <?php
                foreach ($classes as $class) {
                    echo "<h4 style='margin: 20px 0 10px 0; color: #28a745;'>{$class['subject_name']} - Grade {$class['grade_level']} Section {$class['section_name']}</h4>";
                    
                    try {
                        $students_query = "SELECT s.* FROM students s 
                                          WHERE s.section_id = ? AND s.status = 'Active'
                                          ORDER BY s.full_name";
                        $students_stmt = $pdo->prepare($students_query);
                        $students_stmt->execute([$class['section_id']]);
                        $students = $students_stmt->fetchAll();
                        
                        if (count($students) > 0) {
                            echo "<table style='margin-bottom: 30px;'>
                                    <thead>
                                        <tr>
                                            <th>Student Code</th>
                                            <th>Full Name</th>
                                            <th>Date of Birth</th>
                                            <th>Parent Phone</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                            
                            foreach ($students as $student) {
                                echo "<tr>
                                        <td><strong>{$student['student_code']}</strong></td>
                                        <td>{$student['full_name']}</td>
                                        <td>{$student['date_of_birth']}</td>
                                        <td>{$student['parent_phone']}</td>
                                        <td><span style='color: #28a745; font-weight: 600;'>{$student['status']}</span></td>
                                    </tr>";
                            }
                            
                            echo "</tbody></table>";
                        } else {
                            echo "<p style='color: #999; margin-bottom: 20px;'>No students in this section yet.</p>";
                        }
                    } catch (Exception $e) {
                        echo "<p style='color: #e74c3c; margin-bottom: 20px;'>Error loading students for this class.</p>";
                    }
                }
                ?>
            </div>
            <?php endif; ?>
        </div>

<?php require_once 'footer.php'; ?>