<?php
/**
 * ============================================
 * STUDENT PROFILE
 * ============================================
 */

$page_title = 'My Profile';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$student_id = $_SESSION['student_id'];

// Get complete student information
try {
    $student_query = "SELECT s.*, g.grade_level, sec.section_name 
                      FROM students s
                      JOIN grades g ON s.grade_id = g.grade_id
                      JOIN sections sec ON s.section_id = sec.section_id
                      WHERE s.student_id = ?";
    $student_stmt = $pdo->prepare($student_query);
    $student_stmt->execute([$student_id]);
    $student_info = $student_stmt->fetch();

} catch (Exception $e) {
    $student_info = null;
}
?>

        <div class="container">
            <h1>👤 My Profile</h1>
            <p style="color: #999; margin-bottom: 30px;">Your personal and academic information</p>

            <?php if ($student_info): ?>
            <div class="card">
                <div class="card-title">Personal Information</div>
                <div class="profile-info">
                    <div>
                        <p><strong>Student Code:</strong> <?php echo $student_info['student_code']; ?></p>
                        <p><strong>Full Name:</strong> <?php echo $student_info['full_name']; ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo date('F j, Y', strtotime($student_info['date_of_birth'])); ?></p>
                        <p><strong>Gender:</strong> <?php echo $student_info['gender']; ?></p>
                        <p><strong>Status:</strong> <span style="color: #28a745; font-weight: 600;"><?php echo $student_info['status']; ?></span></p>
                    </div>
                    <div>
                        <p><strong>Email:</strong> <?php echo $student_info['email'] ?: 'Not provided'; ?></p>
                        <p><strong>Phone:</strong> <?php echo $student_info['phone'] ?: 'Not provided'; ?></p>
                        <p><strong>Address:</strong> <?php echo $student_info['address'] ?: 'Not provided'; ?></p>
                        <p><strong>Enrolled Date:</strong> <?php echo $student_info['enrolled_date'] ? date('F j, Y', strtotime($student_info['enrolled_date'])) : 'N/A'; ?></p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Academic Information</div>
                <div class="profile-info">
                    <div>
                        <p><strong>Current Grade:</strong> Grade <?php echo $student_info['grade_level']; ?></p>
                        <p><strong>Section:</strong> Section <?php echo $student_info['section_name']; ?></p>
                        <p><strong>Academic Year:</strong> 2026</p>
                        <p><strong>Current Semester:</strong> Semester 1</p>
                    </div>
                    <div>
                        <?php
                        // Get academic statistics
                        try {
                            $grades_count = $pdo->prepare("SELECT COUNT(*) FROM student_grades WHERE student_id = ?");
                            $grades_count->execute([$student_id]);
                            $grades_count = $grades_count->fetchColumn();

                            $subjects_count = $pdo->prepare("
                                SELECT COUNT(DISTINCT c.subject_id) 
                                FROM classes c 
                                WHERE c.section_id = ?
                            ");
                            $subjects_count->execute([$student_info['section_id']]);
                            $subjects_count = $subjects_count->fetchColumn();

                            $average_score = $pdo->prepare("SELECT AVG(total_score) FROM student_grades WHERE student_id = ?");
                            $average_score->execute([$student_id]);
                            $average_score = round($average_score->fetchColumn(), 1);
                        } catch (Exception $e) {
                            $grades_count = $subjects_count = $average_score = 0;
                        }
                        ?>
                        <p><strong>Total Grades:</strong> <?php echo $grades_count; ?></p>
                        <p><strong>Subjects Enrolled:</strong> <?php echo $subjects_count; ?></p>
                        <p><strong>Average Score:</strong> <?php echo $average_score ?: '0'; ?>%</p>
                        <p><strong>Overall Grade:</strong> 
                            <span style="font-weight: bold; color: <?php 
                                echo ($average_score >= 90) ? '#28a745' : 
                                     (($average_score >= 80) ? '#17a2b8' : 
                                     (($average_score >= 70) ? '#ffc107' : 
                                     (($average_score >= 60) ? '#fd7e14' : '#dc3545')));
                            ?>;">
                                <?php 
                                    if ($average_score >= 90) echo 'A (Excellent)';
                                    elseif ($average_score >= 80) echo 'B (Good)';
                                    elseif ($average_score >= 70) echo 'C (Satisfactory)';
                                    elseif ($average_score >= 60) echo 'D (Pass)';
                                    elseif ($average_score > 0) echo 'F (Fail)';
                                    else echo 'No grades yet';
                                ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Parent/Guardian Information</div>
                <div class="profile-info">
                    <div>
                        <p><strong>Parent/Guardian Name:</strong> <?php echo $student_info['parent_name'] ?: 'Not provided'; ?></p>
                        <p><strong>Parent Phone:</strong> <?php echo $student_info['parent_phone'] ?: 'Not provided'; ?></p>
                    </div>
                    <div>
                        <p><strong>Emergency Contact:</strong> <?php echo $student_info['parent_phone'] ?: 'Not provided'; ?></p>
                        <p><strong>Contact Address:</strong> <?php echo $student_info['address'] ?: 'Not provided'; ?></p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">My Subjects</div>
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Subject Code</th>
                            <th>Teacher</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $subjects_query = "SELECT s.subject_name, s.subject_code, t.full_name as teacher_name
                                              FROM classes c
                                              JOIN subjects s ON c.subject_id = s.subject_id
                                              JOIN teachers t ON c.teacher_id = t.teacher_id
                                              WHERE c.section_id = ?
                                              ORDER BY s.subject_name";
                            $subjects_stmt = $pdo->prepare($subjects_query);
                            $subjects_stmt->execute([$student_info['section_id']]);
                            $subjects = $subjects_stmt->fetchAll();
                            
                            if (count($subjects) > 0) {
                                foreach ($subjects as $subject) {
                                    echo "<tr>
                                        <td><strong>{$subject['subject_name']}</strong></td>
                                        <td>{$subject['subject_code']}</td>
                                        <td>{$subject['teacher_name']}</td>
                                        <td><span style='color: #28a745; font-weight: 600;'>Enrolled</span></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align: center; color: #999;'>No subjects assigned yet.</td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='4' style='text-align: center; color: #e74c3c;'>Error loading subjects</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-title">Quick Actions</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                    <a href="grades.php" class="btn">📝 View My Grades</a>
                    <a href="dashboard.php" class="btn btn-secondary">📊 Go to Dashboard</a>
                    <a href="grades.php" class="btn btn-secondary">📋 Print Academic Report</a>
                </div>
            </div>

            <?php else: ?>
            <div class="card">
                <div style="text-align: center; padding: 40px; color: #e74c3c;">
                    <h3>Profile Not Found</h3>
                    <p>Unable to load your profile information. Please contact the administrator.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

<?php require_once 'footer.php'; ?>