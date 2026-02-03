<?php
/**
 * ============================================
 * ADD NEW STUDENT
 * ============================================
 */

$page_title = 'Add New Student';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/StudentManager.php';
require_once __DIR__ . '/../includes/SectionManager.php';
require_once __DIR__ . '/../includes/Validator.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$studentManager = new StudentManager($pdo);
$sectionManager = new SectionManager($pdo);
$error = '';
$success = '';

// Get grades and sections
$grades = $pdo->query("SELECT * FROM grades ORDER BY grade_level")->fetchAll();
$sections = [];

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
    ];

    // Validation
    if (empty($data['full_name'])) {
        $error = 'Full name is required';
    } elseif (empty($data['date_of_birth'])) {
        $error = 'Date of birth is required';
    } elseif ($data['grade_id'] === 0) {
        $error = 'Grade is required';
    } elseif ($data['section_id'] === 0) {
        $error = 'Section is required';
    } else {
        if ($studentManager->addStudent($data)) {
            $success = 'Student added successfully!';
            // Clear form
            $data = [];
        } else {
            $error = 'Error adding student. Please try again.';
        }
    }
}

// Load sections if grade is selected
if (isset($_GET['grade_id'])) {
    $grade_id = (int)$_GET['grade_id'];
    $sections = $pdo->prepare("SELECT * FROM sections WHERE grade_id = ? ORDER BY section_name")->execute([$grade_id]);
    $sections = $pdo->prepare("SELECT * FROM sections WHERE grade_id = ? ORDER BY section_name");
    $sections->execute([$grade_id]);
    $sections = $sections->fetchAll();
}
?>

        <div class="container">
            <h1>➕ Add New Student</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger" style="padding: 12px; background: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success" style="padding: 12px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $success; ?>
                    <br><a href="students.php" style="color: #155724; text-decoration: underline;">← Back to Students</a>
                </div>
            <?php endif; ?>

            <div class="card">
                <form method="POST" style="max-width: 600px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" required 
                                   value="<?php echo $data['full_name'] ?? ''; ?>" placeholder="John Doe">
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth *</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required
                                   value="<?php echo $data['date_of_birth'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender *</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" <?php echo ($data['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($data['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($data['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="grade_id">Grade *</label>
                            <select id="grade_id" name="grade_id" required onchange="loadSections(this.value)">
                                <option value="">Select Grade</option>
                                <?php foreach ($grades as $grade): ?>
                                    <option value="<?php echo $grade['grade_id']; ?>" 
                                            <?php echo ($data['grade_id'] ?? '') === $grade['grade_id'] ? 'selected' : ''; ?>>
                                        Grade <?php echo $grade['grade_level']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section_id">Section *</label>
                            <select id="section_id" name="section_id" required>
                                <option value="">Select Section</option>
                                <?php foreach ($sections as $section): ?>
                                    <option value="<?php echo $section['section_id']; ?>"
                                            <?php echo ($data['section_id'] ?? '') === $section['section_id'] ? 'selected' : ''; ?>>
                                        Section <?php echo $section['section_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo $data['email'] ?? ''; ?>" placeholder="student@example.com">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo $data['phone'] ?? ''; ?>" placeholder="+251911223344">
                        </div>

                        <div class="form-group">
                            <label for="parent_name">Parent/Guardian Name</label>
                            <input type="text" id="parent_name" name="parent_name" 
                                   value="<?php echo $data['parent_name'] ?? ''; ?>" placeholder="Parent Name">
                        </div>

                        <div class="form-group">
                            <label for="parent_phone">Parent Phone</label>
                            <input type="tel" id="parent_phone" name="parent_phone" 
                                   value="<?php echo $data['parent_phone'] ?? ''; ?>" placeholder="+251911223344">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3" placeholder="Student address"><?php echo $data['address'] ?? ''; ?></textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn">✅ Add Student</button>
                        <a href="students.php" class="btn btn-secondary">❌ Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function loadSections(gradeId) {
                if (gradeId) {
                    fetch('get_sections.php?grade_id=' + gradeId)
                        .then(response => response.json())
                        .then(data => {
                            const select = document.getElementById('section_id');
                            select.innerHTML = '<option value="">Select Section</option>';
                            data.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.section_id;
                                option.textContent = 'Section ' + section.section_name;
                                select.appendChild(option);
                            });
                        });
                }
            }
        </script>

<?php require_once 'footer.php'; ?>
