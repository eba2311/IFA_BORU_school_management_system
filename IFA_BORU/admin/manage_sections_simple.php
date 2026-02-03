<?php
/**
 * ============================================
 * SIMPLE SECTION MANAGEMENT
 * ============================================
 */

$page_title = "Manage Sections";
require_once 'header.php';

// Initialize database connection
try {
    require_once '../config/Database.php';
    $db = new Database();
    $pdo = $db->connect();
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}

$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add_section') {
        $grade_id = (int)$_POST['grade_id'];
        $section_name = trim($_POST['section_name']);
        $max_students = (int)$_POST['max_students'];
        
        if (!empty($section_name) && $grade_id > 0) {
            try {
                $stmt = $pdo->prepare("INSERT INTO sections (grade_id, section_name, max_students) VALUES (?, ?, ?)");
                if ($stmt->execute([$grade_id, $section_name, $max_students])) {
                    $message = "Section '$section_name' added successfully!";
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = "Section '$section_name' already exists for this grade!";
                } else {
                    $error = "Error adding section: " . $e->getMessage();
                }
            }
        } else {
            $error = "Please fill in all required fields!";
        }
    }
}

// Get all grades
$grades_stmt = $pdo->query("SELECT * FROM grades ORDER BY grade_level");
$grades = $grades_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all sections with grade information
$sections_stmt = $pdo->query("
    SELECT s.*, g.grade_level, g.grade_name,
           (SELECT COUNT(*) FROM students WHERE section_id = s.section_id) as student_count
    FROM sections s 
    JOIN grades g ON s.grade_id = g.grade_id 
    ORDER BY g.grade_level, s.section_name
");
$sections = $sections_stmt->fetchAll(PDO::FETCH_ASSOC);

// Group sections by grade
$sections_by_grade = [];
foreach ($sections as $section) {
    $sections_by_grade[$section['grade_level']][] = $section;
}
?>

<div class="container">
    <div class="card">
        <div class="card-title">
            <h2>📚 Section Management</h2>
            <p>Manage sections for each grade level</p>
        </div>

        <?php if ($message): ?>
            <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
                ✅ <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div style="padding: 12px; background: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
                ❌ <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Quick Stats -->
        <div class="stats">
            <?php foreach ($grades as $grade): ?>
                <?php 
                $grade_sections = isset($sections_by_grade[$grade['grade_level']]) ? $sections_by_grade[$grade['grade_level']] : [];
                $total_students = array_sum(array_column($grade_sections, 'student_count'));
                ?>
                <div class="stat-card">
                    <h3>Grade <?php echo $grade['grade_level']; ?></h3>
                    <div class="stat-number"><?php echo count($grade_sections); ?></div>
                    <p>Sections</p>
                    <small><?php echo $total_students; ?> students</small>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Add New Section Form -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h3>➕ Add New Section</h3>
            <form method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
                <input type="hidden" name="action" value="add_section">
                
                <div class="form-group">
                    <label for="grade_id">Grade Level *</label>
                    <select name="grade_id" id="grade_id" required>
                        <option value="">Select Grade</option>
                        <?php foreach ($grades as $grade): ?>
                            <option value="<?php echo $grade['grade_id']; ?>">
                                Grade <?php echo $grade['grade_level']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="section_name">Section Name *</label>
                    <input type="text" name="section_name" id="section_name" required 
                           placeholder="A, B, Science, etc." maxlength="50">
                </div>

                <div class="form-group">
                    <label for="max_students">Max Students</label>
                    <input type="number" name="max_students" id="max_students" value="50" min="1" max="100">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">➕ Add Section</button>
                </div>
            </form>
        </div>

        <!-- Current Sections -->
        <h3>📋 Current Sections</h3>
        
        <?php foreach ($grades as $grade): ?>
            <div style="margin-bottom: 30px; padding: 20px; background: #f9f9f9; border-radius: 8px;">
                <h4 style="margin-bottom: 15px; color: #333; border-bottom: 2px solid #667eea; padding-bottom: 8px;">
                    📚 Grade <?php echo $grade['grade_level']; ?> - <?php echo $grade['grade_name']; ?>
                </h4>
                
                <?php if (isset($sections_by_grade[$grade['grade_level']])): ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                        <?php foreach ($sections_by_grade[$grade['grade_level']] as $section): ?>
                            <div style="background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #667eea;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <h5 style="margin: 0; color: #333;">Section <?php echo $section['section_name']; ?></h5>
                                </div>
                                <div style="font-size: 14px; color: #666;">
                                    <p style="margin: 5px 0;"><strong>Max Students:</strong> <?php echo $section['max_students']; ?></p>
                                    <p style="margin: 5px 0;"><strong>Current Students:</strong> <?php echo $section['student_count']; ?></p>
                                    <p style="margin: 5px 0;"><strong>Available:</strong> 
                                        <span style="color: #27ae60; font-weight: bold;">
                                            <?php echo $section['max_students'] - $section['student_count']; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="text-align: center; color: #666; font-style: italic; padding: 20px;">
                        No sections found for this grade. Add sections using the form above.
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <!-- Quick Actions -->
        <div style="text-align: center; margin-top: 30px; padding: 20px; background: #e9ecef; border-radius: 8px;">
            <h4>🚀 Quick Actions</h4>
            <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 15px;">
                <a href="students.php" class="btn">👨‍🎓 Manage Students</a>
                <a href="teachers.php" class="btn">👨‍🏫 Manage Teachers</a>
                <a href="classes.php" class="btn">🎓 Manage Classes</a>
                <a href="dashboard.php" class="btn btn-secondary">🏠 Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<style>
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.stat-card h3 {
    margin-bottom: 10px;
    font-size: 14px;
    opacity: 0.9;
}

.stat-number {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 5px;
}

@media (max-width: 768px) {
    .stats {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<?php require_once 'footer.php'; ?>