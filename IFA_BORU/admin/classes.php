<?php
/**
 * ============================================
 * ADMIN - CLASSES MANAGEMENT
 * ============================================
 */

$page_title = 'Classes Management';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/Validator.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $teacher_id = (int)($_POST['teacher_id'] ?? 0);
        $subject_id = (int)($_POST['subject_id'] ?? 0);
        $section_id = (int)($_POST['section_id'] ?? 0);
        $academic_year = Validator::sanitize($_POST['academic_year'] ?? '2026');
        $semester = (int)($_POST['semester'] ?? 1);

        if ($teacher_id > 0 && $subject_id > 0 && $section_id > 0) {
            try {
                $query = "INSERT INTO classes (teacher_id, subject_id, section_id, academic_year, semester) 
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$teacher_id, $subject_id, $section_id, $academic_year, $semester]);
                $success = 'Class created successfully!';
            } catch (PDOException $e) {
                $error = 'Error creating class: ' . $e->getMessage();
            }
        } else {
            $error = 'All fields are required';
        }
    }
}

// Get all classes with details
try {
    $classes = $pdo->query("SELECT c.*, t.full_name as teacher_name, s.subject_name, sec.section_name, g.grade_level
                            FROM classes c
                            JOIN teachers t ON c.teacher_id = t.teacher_id
                            JOIN subjects s ON c.subject_id = s.subject_id
                            JOIN sections sec ON c.section_id = sec.section_id
                            JOIN grades g ON sec.grade_id = g.grade_id
                            ORDER BY c.academic_year DESC, c.semester DESC")->fetchAll();
} catch (Exception $e) {
    $classes = [];
}

try {
    $teachers = $pdo->query("SELECT * FROM teachers WHERE status = 'Active' ORDER BY full_name")->fetchAll();
    $subjects = $pdo->query("SELECT * FROM subjects ORDER BY subject_name")->fetchAll();
    $sections = $pdo->query("SELECT sec.*, g.grade_level FROM sections sec JOIN grades g ON sec.grade_id = g.grade_id ORDER BY g.grade_level, sec.section_name")->fetchAll();
} catch (Exception $e) {
    $teachers = $subjects = $sections = [];
}
?>

        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1>🎓 Classes Management</h1>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger" style="padding: 12px; background: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success" style="padding: 12px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-title">Create New Class</div>
                <form method="POST" style="max-width: 600px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group">
                        <label for="teacher_id">Teacher *</label>
                        <select id="teacher_id" name="teacher_id" required>
                            <option value="">Select Teacher</option>
                            <?php foreach ($teachers as $teacher): ?>
                                <option value="<?php echo $teacher['teacher_id']; ?>"><?php echo $teacher['full_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject_id">Subject *</label>
                        <select id="subject_id" name="subject_id" required>
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject['subject_id']; ?>"><?php echo $subject['subject_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="section_id">Section/Grade *</label>
                        <select id="section_id" name="section_id" required>
                            <option value="">Select Section</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?php echo $section['section_id']; ?>">Grade <?php echo $section['grade_level']; ?> - Section <?php echo $section['section_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="academic_year">Academic Year</label>
                        <input type="text" id="academic_year" name="academic_year" value="2026" placeholder="2026">
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select id="semester" name="semester">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>
                    </div>

                    <button type="submit" class="btn" style="grid-column: 1 / -1;">➕ Create Class</button>
                </form>
            </div>

            <div class="card">
                <div class="card-title">Classes List (<?php echo count($classes); ?>)</div>
                <?php if (empty($teachers) || empty($subjects)): ?>
                    <div style="text-align: center; padding: 20px; color: #666;">
                        <p>⚠️ To create classes, you need:</p>
                        <ul style="list-style: none; padding: 0;">
                            <?php if (empty($teachers)): ?>
                                <li>• <a href="add_teacher.php">Add at least one teacher</a></li>
                            <?php endif; ?>
                            <?php if (empty($subjects)): ?>
                                <li>• <a href="subjects.php">Add at least one subject</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php else: ?>
                    <table>
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
                                echo "<tr><td colspan='6' style='text-align: center; color: #999;'>No classes yet. Create your first class above.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

<?php require_once 'footer.php'; ?>