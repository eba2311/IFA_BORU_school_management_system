<?php
/**
 * ============================================
 * ADMIN - SUBJECTS MANAGEMENT
 * ============================================
 */

$page_title = 'Subjects Management';
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
        $subject_name = Validator::sanitize($_POST['subject_name'] ?? '');
        $subject_code = Validator::sanitize($_POST['subject_code'] ?? '');
        $description = Validator::sanitize($_POST['description'] ?? '');

        if (!empty($subject_name) && !empty($subject_code)) {
            try {
                $query = "INSERT INTO subjects (subject_name, subject_code, description) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$subject_name, $subject_code, $description]);
                $success = 'Subject added successfully!';
            } catch (PDOException $e) {
                $error = 'Error adding subject: ' . $e->getMessage();
            }
        } else {
            $error = 'Subject name and code are required';
        }
    } elseif ($_POST['action'] === 'delete') {
        $subject_id = (int)($_POST['subject_id'] ?? 0);
        if ($subject_id > 0) {
            try {
                $query = "DELETE FROM subjects WHERE subject_id = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$subject_id]);
                $success = 'Subject deleted successfully!';
            } catch (PDOException $e) {
                $error = 'Error deleting subject: ' . $e->getMessage();
            }
        }
    }
}

// Get all subjects
$subjects = $pdo->query("SELECT * FROM subjects ORDER BY subject_name")->fetchAll();
?>

        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1>📚 Subjects Management</h1>
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
                <div class="card-title">Add New Subject</div>
                <form method="POST" style="max-width: 400px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-group">
                        <label for="subject_name">Subject Name *</label>
                        <input type="text" id="subject_name" name="subject_name" required placeholder="Mathematics">
                    </div>

                    <div class="form-group">
                        <label for="subject_code">Subject Code *</label>
                        <input type="text" id="subject_code" name="subject_code" required placeholder="MATH">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="2" placeholder="Subject description"></textarea>
                    </div>

                    <button type="submit" class="btn" style="grid-column: 1 / -1;">➕ Add Subject</button>
                </form>
            </div>

            <div class="card">
                <div class="card-title">Existing Subjects (<?php echo count($subjects); ?>)</div>
                <table>
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($subjects) > 0) {
                            foreach ($subjects as $subject) {
                                echo "<tr>
                                    <td><strong>{$subject['subject_code']}</strong></td>
                                    <td>{$subject['subject_name']}</td>
                                    <td>{$subject['description']}</td>
                                    <td>
                                        <form method='POST' style='display: inline;'>
                                            <input type='hidden' name='action' value='delete'>
                                            <input type='hidden' name='subject_id' value='{$subject['subject_id']}'>
                                            <button type='submit' class='btn btn-small btn-danger' onclick='return confirm(\"Are you sure?\");'>🗑️ Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align: center; color: #999;'>No subjects yet. Add your first subject above.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php require_once 'footer.php'; ?>