<?php
/**
 * ============================================
 * ADMIN - SETTINGS
 * ============================================
 */

$page_title = 'System Settings';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/Validator.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if ($key !== 'action') {
            try {
                $query = "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                          ON DUPLICATE KEY UPDATE setting_value = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([Validator::sanitize($key), Validator::sanitize($value), Validator::sanitize($value)]);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
    $success = 'Settings updated successfully!';
}

// Get current settings
try {
    $settings_result = $pdo->query("SELECT setting_key, setting_value FROM settings");
    $settings = [];
    while ($row = $settings_result->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (Exception $e) {
    $settings = [];
}
?>

        <div class="container">
            <h1>⚙️ System Settings</h1>

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
                <div class="card-title">School Configuration</div>
                <form method="POST">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="school_name">School Name</label>
                            <input type="text" id="school_name" name="school_name" value="<?php echo $settings['school_name'] ?? 'IFA BORU AMURU Secondary School'; ?>" placeholder="IFA BORU AMURU">
                        </div>

                        <div class="form-group">
                            <label for="academic_year">Current Academic Year</label>
                            <input type="text" id="academic_year" name="academic_year" value="<?php echo $settings['academic_year'] ?? '2026'; ?>" placeholder="2026">
                        </div>

                        <div class="form-group">
                            <label for="current_semester">Current Semester</label>
                            <select id="current_semester" name="current_semester">
                                <option value="1" <?php echo ($settings['current_semester'] ?? '1') === '1' ? 'selected' : ''; ?>>Semester 1</option>
                                <option value="2" <?php echo ($settings['current_semester'] ?? '') === '2' ? 'selected' : ''; ?>>Semester 2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="max_assignment">Max Assignment Score</label>
                            <input type="number" id="max_assignment" name="max_assignment" value="<?php echo $settings['max_assignment'] ?? '10'; ?>">
                        </div>

                        <div class="form-group">
                            <label for="max_test">Max Test Score</label>
                            <input type="number" id="max_test" name="max_test" value="<?php echo $settings['max_test'] ?? '10'; ?>">
                        </div>

                        <div class="form-group">
                            <label for="max_midterm">Max Mid-term Exam Score</label>
                            <input type="number" id="max_midterm" name="max_midterm" value="<?php echo $settings['max_midterm'] ?? '20'; ?>">
                        </div>

                        <div class="form-group">
                            <label for="max_final">Max Final Exam Score</label>
                            <input type="number" id="max_final" name="max_final" value="<?php echo $settings['max_final'] ?? '60'; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="grading_scale">Grading Scale</label>
                        <textarea id="grading_scale" name="grading_scale" rows="4" placeholder="A=90-100, B=80-89, C=70-79, D=60-69, F=0-59"><?php echo $settings['grading_scale'] ?? 'A=90-100, B=80-89, C=70-79, D=60-69, F=0-59'; ?></textarea>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn">💾 Save Settings</button>
                    </div>
                </form>
            </div>

            <div class="card" style="margin-top: 20px;">
                <div class="card-title">System Information</div>
                <table>
                    <tr>
                        <td><strong>PHP Version:</strong></td>
                        <td><?php echo phpversion(); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Server:</strong></td>
                        <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Database:</strong></td>
                        <td>MySQL via PDO</td>
                    </tr>
                    <tr>
                        <td><strong>System Status:</strong></td>
                        <td><span style="color: #28a745; font-weight: 600;">✅ Online</span></td>
                    </tr>
                </table>
            </div>

            <div class="card" style="margin-top: 20px;">
                <div class="card-title">Quick Actions</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <a href="dashboard.php" class="btn btn-secondary">📊 Go to Dashboard</a>
                    <a href="students.php" class="btn btn-secondary">👨‍🎓 Manage Students</a>
                    <a href="teachers.php" class="btn btn-secondary">👨‍🏫 Manage Teachers</a>
                    <a href="reports.php" class="btn btn-secondary">📋 View Reports</a>
                </div>
            </div>
        </div>

<?php require_once 'footer.php'; ?>