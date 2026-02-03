<?php
/**
 * ============================================
 * EDIT TEACHER
 * ============================================
 */

$page_title = 'Edit Teacher';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/TeacherManager.php';
require_once __DIR__ . '/../includes/Validator.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$teacherManager = new TeacherManager($pdo);
$teacher_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$teacher = null;
$error = '';
$success = '';

if ($teacher_id === 0) {
    header('Location: teachers.php');
    exit;
}

$teacher = $teacherManager->getTeacherById($teacher_id);
if (!$teacher) {
    $error = 'Teacher not found';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => Validator::sanitize($_POST['full_name'] ?? ''),
        'email' => Validator::sanitize($_POST['email'] ?? ''),
        'phone' => Validator::sanitize($_POST['phone'] ?? ''),
        'date_of_birth' => Validator::sanitize($_POST['date_of_birth'] ?? ''),
        'gender' => Validator::sanitize($_POST['gender'] ?? 'Male'),
        'address' => Validator::sanitize($_POST['address'] ?? ''),
        'status' => Validator::sanitize($_POST['status'] ?? 'Active'),
    ];

    if (empty($data['full_name'])) {
        $error = 'Full name is required';
    } else {
        if ($teacherManager->updateTeacher($teacher_id, $data)) {
            $success = 'Teacher updated successfully!';
            $teacher = $teacherManager->getTeacherById($teacher_id);
        } else {
            $error = 'Error updating teacher. Please try again.';
        }
    }
}
?>

        <div class="container">
            <h1>✏️ Edit Teacher</h1>

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

            <?php if ($teacher): ?>
            <div class="card">
                <form method="POST" style="max-width: 600px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Teacher Code</label>
                            <input type="text" value="<?php echo $teacher['teacher_code']; ?>" disabled 
                                   style="background: #f0f0f0;">
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" value="<?php echo $teacher['username']; ?>" disabled 
                                   style="background: #f0f0f0;">
                        </div>

                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" required 
                                   value="<?php echo $teacher['full_name']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required
                                   value="<?php echo $teacher['email']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo $teacher['phone']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="Male" <?php echo $teacher['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo $teacher['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo $teacher['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                   value="<?php echo $teacher['date_of_birth']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="Active" <?php echo $teacher['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                                <option value="Inactive" <?php echo $teacher['status'] === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3"><?php echo $teacher['address']; ?></textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn">✅ Update Teacher</button>
                        <a href="teachers.php" class="btn btn-secondary">❌ Cancel</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>

<?php require_once 'footer.php'; ?>
