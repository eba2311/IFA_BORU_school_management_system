<?php
/**
 * ============================================
 * TEACHER - ENTER GRADES
 * ============================================
 */

$page_title = 'Enter Grades';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/Validator.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$teacher_id = $_SESSION['teacher_id'];
$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$error = '';
$success = '';

// Handle grade submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_grades'])) {
    $class_id = (int)$_POST['class_id'];
    $academic_year = Validator::sanitize($_POST['academic_year'] ?? '2026');
    $semester = (int)($_POST['semester'] ?? 1);
    
    foreach ($_POST['grades'] as $student_id => $grades) {
        $student_id = (int)$student_id;
        $assignment = (float)($grades['assignment'] ?? 0);
        $test = (float)($grades['test'] ?? 0);
        $mid_exam = (float)($grades['mid_exam'] ?? 0);
        $final_exam = (float)($grades['final_exam'] ?? 0);
        
        // Calculate total and grade letter
        $total = $assignment + $test + $mid_exam + $final_exam;
        $grade_letter = 'F';
        if ($total >= 90) $grade_letter = 'A';
        elseif ($total >= 80) $grade_letter = 'B';
        elseif ($total >= 70) $grade_letter = 'C';
        elseif ($total >= 60) $grade_letter = 'D';
        
        try {
            // Insert or update grades
            $query = "INSERT INTO student_grades 
                      (student_id, class_id, assignment_score, test_score, mid_exam_score, final_exam_score, total_score, grade_letter, academic_year, semester)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE
                      assignment_score = VALUES(assignment_score),
                      test_score = VALUES(test_score),
                      mid_exam_score = VALUES(mid_exam_score),
                      final_exam_score = VALUES(final_exam_score),
                      total_score = VALUES(total_score),
                      grade_letter = VALUES(grade_letter),
                      updated_at = CURRENT_TIMESTAMP";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([$student_id, $class_id, $assignment, $test, $mid_exam, $final_exam, $total, $grade_letter, $academic_year, $semester]);
        } catch (Exception $e) {
            $error = 'Error saving grades: ' . $e->getMessage();
            break;
        }
    }
    
    if (!$error) {
        $success = 'Grades saved successfully!';
    }
}

// Get teacher's classes
$classes = [];
try {
    $query = "SELECT c.*, s.subject_name, sec.section_name, g.grade_level
              FROM classes c
              JOIN subjects s ON c.subject_id = s.subject_id
              JOIN sections sec ON c.section_id = sec.section_id
              JOIN grades g ON sec.grade_id = g.grade_id
              WHERE c.teacher_id = ?
              ORDER BY g.grade_level, sec.section_name, s.subject_name";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$teacher_id]);
    $classes = $stmt->fetchAll();
} catch (Exception $e) {
    $classes = [];
}

// Get students for selected class
$students = [];
$selected_class = null;
if ($class_id > 0) {
    try {
        // Get class info
        $class_query = "SELECT c.*, s.subject_name, sec.section_name, g.grade_level
                        FROM classes c
                        JOIN subjects s ON c.subject_id = s.subject_id
                        JOIN sections sec ON c.section_id = sec.section_id
                        JOIN grades g ON sec.grade_id = g.grade_id
                        WHERE c.class_id = ? AND c.teacher_id = ?";
        $class_stmt = $pdo->prepare($class_query);
        $class_stmt->execute([$class_id, $teacher_id]);
        $selected_class = $class_stmt->fetch();
        
        if ($selected_class) {
            // Get students with existing grades
            $students_query = "SELECT s.*, 
                                      sg.assignment_score, sg.test_score, sg.mid_exam_score, 
                                      sg.final_exam_score, sg.total_score, sg.grade_letter
                               FROM students s
                               LEFT JOIN student_grades sg ON s.student_id = sg.student_id AND sg.class_id = ?
                               WHERE s.section_id = ? AND s.status = 'Active'
                               ORDER BY s.full_name";
            $students_stmt = $pdo->prepare($students_query);
            $students_stmt->execute([$class_id, $selected_class['section_id']]);
            $students = $students_stmt->fetchAll();
        }
    } catch (Exception $e) {
        $error = 'Error loading class data';
    }
}
?>

        <div class="container">
            <h1>📝 Enter Grades</h1>
            <p style="color: #999; margin-bottom: 30px;">Enter and manage student grades</p>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-title">Select Class</div>
                <form method="GET" style="display: flex; gap: 10px; align-items: end;">
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label for="class_id">Choose Class:</label>
                        <select id="class_id" name="class_id" onchange="this.form.submit()">
                            <option value="">Select a class</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?php echo $class['class_id']; ?>" 
                                        <?php echo ($class_id == $class['class_id']) ? 'selected' : ''; ?>>
                                    <?php echo $class['subject_name']; ?> - Grade <?php echo $class['grade_level']; ?> Section <?php echo $class['section_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>

            <?php if ($selected_class && count($students) > 0): ?>
            <div class="card">
                <div class="card-title">
                    Enter Grades: <?php echo $selected_class['subject_name']; ?> - Grade <?php echo $selected_class['grade_level']; ?> Section <?php echo $selected_class['section_name']; ?>
                </div>
                
                <form method="POST">
                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                    <input type="hidden" name="save_grades" value="1">
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label for="academic_year">Academic Year:</label>
                            <input type="text" id="academic_year" name="academic_year" value="2026">
                        </div>
                        <div class="form-group">
                            <label for="semester">Semester:</label>
                            <select id="semester" name="semester">
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                            </select>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Assignment<br><small>(0-10)</small></th>
                                    <th>Test<br><small>(0-10)</small></th>
                                    <th>Mid Exam<br><small>(0-20)</small></th>
                                    <th>Final Exam<br><small>(0-60)</small></th>
                                    <th>Total<br><small>(0-100)</small></th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><strong><?php echo $student['full_name']; ?></strong><br>
                                        <small><?php echo $student['student_code']; ?></small></td>
                                    <td>
                                        <input type="number" name="grades[<?php echo $student['student_id']; ?>][assignment]" 
                                               value="<?php echo $student['assignment_score'] ?? ''; ?>" 
                                               min="0" max="10" step="0.1" style="width: 80px;">
                                    </td>
                                    <td>
                                        <input type="number" name="grades[<?php echo $student['student_id']; ?>][test]" 
                                               value="<?php echo $student['test_score'] ?? ''; ?>" 
                                               min="0" max="10" step="0.1" style="width: 80px;">
                                    </td>
                                    <td>
                                        <input type="number" name="grades[<?php echo $student['student_id']; ?>][mid_exam]" 
                                               value="<?php echo $student['mid_exam_score'] ?? ''; ?>" 
                                               min="0" max="20" step="0.1" style="width: 80px;">
                                    </td>
                                    <td>
                                        <input type="number" name="grades[<?php echo $student['student_id']; ?>][final_exam]" 
                                               value="<?php echo $student['final_exam_score'] ?? ''; ?>" 
                                               min="0" max="60" step="0.1" style="width: 80px;">
                                    </td>
                                    <td><strong><?php echo $student['total_score'] ?? '0'; ?></strong></td>
                                    <td><strong style="color: <?php 
                                        $grade = $student['grade_letter'] ?? 'F';
                                        echo ($grade === 'A') ? '#28a745' : (($grade === 'B') ? '#17a2b8' : (($grade === 'C') ? '#ffc107' : (($grade === 'D') ? '#fd7e14' : '#dc3545')));
                                    ?>;"><?php echo $grade; ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 20px; text-align: center;">
                        <button type="submit" class="btn">💾 Save All Grades</button>
                    </div>
                </form>

                <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                    <h4>Grading Scale:</h4>
                    <p><strong>A:</strong> 90-100 (Excellent) | <strong>B:</strong> 80-89 (Good) | <strong>C:</strong> 70-79 (Satisfactory) | <strong>D:</strong> 60-69 (Pass) | <strong>F:</strong> 0-59 (Fail)</p>
                </div>
            </div>
            <?php elseif ($selected_class): ?>
            <div class="card">
                <div style="text-align: center; padding: 40px; color: #666;">
                    <h3>No Students Found</h3>
                    <p>There are no active students in this class section.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

<?php require_once 'footer.php'; ?>