<?php
/**
 * ============================================
 * ADD NEW TEACHER
 * ============================================
 */

$page_title = 'Add New Teacher';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/TeacherManager.php';
require_once __DIR__ . '/../includes/Validator.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$teacherManager = new TeacherManager($pdo);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => Validator::sanitize($_POST['full_name'] ?? ''),
        'email' => Validator::sanitize($_POST['email'] ?? ''),
        'phone' => Validator::sanitize($_POST['phone'] ?? ''),
        'username' => Validator::sanitize($_POST['username'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'password_confirm' => $_POST['password_confirm'] ?? '',
        'date_of_birth' => Validator::sanitize($_POST['date_of_birth'] ?? ''),
        'gender' => Validator::sanitize($_POST['gender'] ?? 'Male'),
        'address' => Validator::sanitize($_POST['address'] ?? ''),
    ];

    // Validation
    if (empty($data['full_name'])) {
        $error = 'Full name is required';
    } elseif (empty($data['email']) || !Validator::isValidEmail($data['email'])) {
        $error = 'Valid email is required';
    } elseif (empty($data['username']) || !Validator::isValidUsername($data['username'])) {
        $error = 'Valid username (3-20 characters) is required';
    } elseif (empty($data['password'])) {
        $error = 'Password is required';
    } elseif ($data['password'] !== $data['password_confirm']) {
        $error = 'Passwords do not match';
    } else {
        if ($teacherManager->addTeacher($data)) {
            $success = 'Teacher added successfully!';
            $data = [];
        } else {
            $error = 'Error adding teacher. Please try again.';
        }
    }
}
?>

        <div class="container">
            <h1>➕ Add New Teacher</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger" style="padding: 12px; background: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success" style="padding: 12px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $success; ?>
                    <br><a href="teachers.php" style="color: #155724; text-decoration: underline;">← Back to Teachers</a>
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
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required
                                   value="<?php echo $data['email'] ?? ''; ?>" placeholder="teacher@ifaboru.edu.et">
                        </div>

                        <div class="form-group">
                            <label for="username">Username *</label>
                            <input type="text" id="username" name="username" required
                                   value="<?php echo $data['username'] ?? ''; ?>" placeholder="johndoe">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo $data['phone'] ?? ''; ?>" placeholder="+251911223344">
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="Male" <?php echo ($data['gender'] ?? 'Male') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($data['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($data['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                   value="<?php echo $data['date_of_birth'] ?? ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password" id="password" name="password" required placeholder="Strong password">
                        </div>

                        <div class="form-group">
                            <label for="password_confirm">Confirm Password *</label>
                            <input type="password" id="password_confirm" name="password_confirm" required placeholder="Confirm password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3" placeholder="Teacher address"><?php echo $data['address'] ?? ''; ?></textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn">✅ Add Teacher</button>
                        <a href="teachers.php" class="btn btn-secondary">❌ Cancel</a>
                    </div>
                </form>
            </div>
        </div>

<?php require_once 'footer.php'; ?>
