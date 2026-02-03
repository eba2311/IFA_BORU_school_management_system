<?php
/**
 * ============================================
 * EDIT STUDENT
 * ============================================
 */

$page_title = 'Edit Student';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/StudentManager.php';
require_once __DIR__ . '/../includes/Validator.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$studentManager = new StudentManager($pdo);
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student = null;
$error = '';
$success = '';

if ($student_id === 0) {
    header('Location: students.php');
    exit;
}

$student = $studentManager->getStudentById($student_id);
if (!$student) {
    $error = 'Student not found';
}

$grades = $pdo->query("SELECT * FROM grades ORDER BY grade_level")->fetchAll();
$sections_query = $pdo->prepare("SELECT * FROM sections WHERE grade_id = ? ORDER BY section_name");
$sections_query->execute([$student['grade_id'] ?? 0]);
$sections = $sections_query->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => Validator::sanitize($_POST['full_name'] ?? ''),
        'date_of_birth' => Validator::sanitize($_POST['date_of_birth'] ?? ''),
        'gender' => Validator::sanitize($_POST['gender'] ?? ''),
        'grade_id' => (int)($_POST['grade_id'] ?? 0),
        'section_id' => (int)($_POST['section_id'] ?? 0),
        'parent_name' => Validator::sanitize($_POST['parent_name'] ?? ''),
        'parent_phone' => Validator::sanitize($_POST['parent_phone'] ?? ''),
        'address' => Validator::sanitize($_POST['address'] ?? ''),
        'email' => Validator::sanitize($_POST['email'] ?? ''),
        'phone' => Validator::sanitize($_POST['phone'] ?? ''),
        'status' => Validator::sanitize($_POST['status'] ?? 'Active'),
    ];

    if (empty($data['full_name'])) {
        $error = 'Full name is required';
    } elseif (empty($data['date_of_birth'])) {
        $error = 'Date of birth is required';
    } else {
        if ($studentManager->updateStudent($student_id, $data)) {
            $success = 'Student updated successfully!';
            $student = $studentManager->getStudentById($student_id);
        } else {
            $error = 'Error updating student. Please try again.';
        }
    }
}

if (!$student && !$error) {
    $error = 'Student not found';
}
?>

        <div class="container">
            <h1>✏️ Edit Student</h1>

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

            <?php if ($student): ?>
            <div class="card">
                <form method="POST" style="max-width: 600px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Student Code</label>
                            <input type="text" value="<?php echo $student['student_code']; ?>" disabled 
                                   style="background: #f0f0f0;">
                        </div>

                        <div class="form-group">
                            <label>Enrolled Date</label>
                            <input type="date" value="<?php echo $student['enrolled_date']; ?>" disabled 
                                   style="background: #f0f0f0;">
                        </div>

                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" required 
                                   value="<?php echo $student['full_name']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth *</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required
                                   value="<?php echo $student['date_of_birth']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="Male" <?php echo $student['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo $student['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo $student['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required>
                                <option value="Active" <?php echo $student['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                                <option value="Inactive" <?php echo $student['status'] === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="grade_id">Grade *</label>
                            <select id="grade_id" name="grade_id" required>
                                <?php foreach ($grades as $grade): ?>
                                    <option value="<?php echo $grade['grade_id']; ?>" 
                                            <?php echo $student['grade_id'] == $grade['grade_id'] ? 'selected' : ''; ?>>
                                        Grade <?php echo $grade['grade_level']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section_id">Section *</label>
                            <select id="section_id" name="section_id" required>
                                <?php foreach ($sections as $section): ?>
                                    <option value="<?php echo $section['section_id']; ?>"
                                            <?php echo $student['section_id'] == $section['section_id'] ? 'selected' : ''; ?>>
                                        Section <?php echo $section['section_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo $student['email']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo $student['phone']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="parent_name">Parent/Guardian Name</label>
                            <input type="text" id="parent_name" name="parent_name" value="<?php echo $student['parent_name']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="parent_phone">Parent Phone</label>
                            <input type="tel" id="parent_phone" name="parent_phone" value="<?php echo $student['parent_phone']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3"><?php echo $student['address']; ?></textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn">✅ Update Student</button>
                        <a href="students.php" class="btn btn-secondary">❌ Cancel</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>

<?php require_once 'footer.php'; ?>
