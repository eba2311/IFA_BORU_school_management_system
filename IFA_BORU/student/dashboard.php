<?php
/**
 * ============================================
 * STUDENT DASHBOARD
 * ============================================
 */

$page_title = 'Student Dashboard';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$student_id = $_SESSION['student_id'];

// Get student information
try {
    $student_query = "SELECT s.*, g.grade_level, sec.section_name 
                      FROM students s
                      JOIN grades g ON s.grade_id = g.grade_id
                      JOIN sections sec ON s.section_id = sec.section_id
                      WHERE s.student_id = ?";
    $student_stmt = $pdo->prepare($student_query);
    $student_stmt->execute([$student_id]);
    $student_info = $student_stmt->fetch();

    // Get student statistics
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

    // Get student rank and class average
    $rank_query = "
        SELECT student_rank, class_average, total_students
        FROM (
            SELECT s.student_id,
                   RANK() OVER (PARTITION BY s.grade_id, s.section_id ORDER BY AVG(sg.total_score) DESC) as student_rank,
                   AVG(AVG(sg.total_score)) OVER (PARTITION BY s.grade_id, s.section_id) as class_average,
                   COUNT(*) OVER (PARTITION BY s.grade_id, s.section_id) as total_students
            FROM students s
            LEFT JOIN student_grades sg ON s.student_id = sg.student_id
            WHERE s.grade_id = ? AND s.section_id = ?
            GROUP BY s.student_id
            HAVING AVG(sg.total_score) IS NOT NULL
        ) ranked_students
        WHERE student_id = ?
    ";
    $rank_stmt = $pdo->prepare($rank_query);
    $rank_stmt->execute([$student_info['grade_id'], $student_info['section_id'], $student_id]);
    $rank_data = $rank_stmt->fetch();
    
    $student_rank = $rank_data['student_rank'] ?? null;
    $class_average = $rank_data ? round($rank_data['class_average'], 1) : null;
    $total_students = $rank_data['total_students'] ?? 0;

} catch (Exception $e) {
    $grades_count = $subjects_count = $average_score = 0;
    $student_info = null;
    $student_rank = $class_average = $total_students = null;
}
?>

        <div class="container">
            <h1>Welcome, <?php echo $_SESSION['student_name']; ?>! 👋</h1>
            <p style="color: #999; margin-bottom: 30px;">Here's your academic overview</p>

            <div class="stats">
                <div class="stat-card" style="border-left-color: #17a2b8;">
                    <h3>My Grades</h3>
                    <div class="stat-number"><?php echo $grades_count; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #28a745;">
                    <h3>Subjects</h3>
                    <div class="stat-number"><?php echo $subjects_count; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #ffc107;">
                    <h3>My Average</h3>
                    <div class="stat-number"><?php echo $average_score ?: '0'; ?>%</div>
                </div>
                <div class="stat-card" style="border-left-color: <?php echo ($student_rank == 1) ? '#ffd700' : (($student_rank <= 3) ? '#c0c0c0' : '#dc3545'); ?>;">
                    <h3>Class Rank</h3>
                    <div class="stat-number">
                        <?php if ($student_rank): ?>
                            <?php if ($student_rank == 1): ?>🥇<?php elseif ($student_rank == 2): ?>🥈<?php elseif ($student_rank == 3): ?>🥉<?php endif; ?>
                            <?php echo $student_rank; ?>/<?php echo $total_students; ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Quick Actions</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                    <a href="profile.php" class="btn">👤 View My Profile</a>
                    <a href="grades.php" class="btn">📝 View My Grades</a>
                    <a href="grades.php" class="btn btn-secondary">📊 Academic Report</a>
                </div>
            </div>

            <?php if ($student_info): ?>
            <div class="card">
                <div class="card-title">Student Information</div>
                <div class="profile-info">
                    <div>
                        <p><strong>Student Code:</strong> <?php echo $student_info['student_code']; ?></p>
                        <p><strong>Full Name:</strong> <?php echo $student_info['full_name']; ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo $student_info['date_of_birth']; ?></p>
                    </div>
                    <div>
                        <p><strong>Grade:</strong> Grade <?php echo $student_info['grade_level']; ?></p>
                        <p><strong>Section:</strong> Section <?php echo $student_info['section_name']; ?></p>
                        <p><strong>Status:</strong> <span style="color: #28a745; font-weight: 600;"><?php echo $student_info['status']; ?></span></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-title">Recent Grades</div>
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Assignment</th>
                            <th>Test</th>
                            <th>Mid Exam</th>
                            <th>Final Exam</th>
                            <th>Total</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $query = "SELECT sg.*, s.subject_name
                                      FROM student_grades sg
                                      JOIN classes c ON sg.class_id = c.class_id
                                      JOIN subjects s ON c.subject_id = s.subject_id
                                      WHERE sg.student_id = ?
                                      ORDER BY sg.updated_at DESC
                                      LIMIT 5";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute([$student_id]);
                            $recent_grades = $stmt->fetchAll();
                            
                            if (count($recent_grades) > 0) {
                                foreach ($recent_grades as $grade) {
                                    $grade_color = '#dc3545'; // F - Red
                                    if ($grade['grade_letter'] === 'A') $grade_color = '#28a745'; // Green
                                    elseif ($grade['grade_letter'] === 'B') $grade_color = '#17a2b8'; // Blue
                                    elseif ($grade['grade_letter'] === 'C') $grade_color = '#ffc107'; // Yellow
                                    elseif ($grade['grade_letter'] === 'D') $grade_color = '#fd7e14'; // Orange
                                    
                                    echo "<tr>
                                        <td><strong>{$grade['subject_name']}</strong></td>
                                        <td>{$grade['assignment_score']}</td>
                                        <td>{$grade['test_score']}</td>
                                        <td>{$grade['mid_exam_score']}</td>
                                        <td>{$grade['final_exam_score']}</td>
                                        <td><strong>{$grade['total_score']}</strong></td>
                                        <td><strong style='color: {$grade_color};'>{$grade['grade_letter']}</strong></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align: center; color: #999;'>No grades recorded yet. Check back later.</td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='7' style='text-align: center; color: #e74c3c;'>Error loading grades</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-title">Academic Performance</div>
                <?php if ($average_score > 0): ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: center;">
                        <div style="text-align: center; padding: 20px;">
                            <div style="font-size: 48px; font-weight: bold; color: <?php 
                                echo ($average_score >= 90) ? '#28a745' : 
                                     (($average_score >= 80) ? '#17a2b8' : 
                                     (($average_score >= 70) ? '#ffc107' : 
                                     (($average_score >= 60) ? '#fd7e14' : '#dc3545')));
                            ?>;">
                                <?php echo $average_score; ?>%
                            </div>
                            <p style="color: #666; margin-top: 10px;">My Average Score</p>
                            <p style="margin-top: 15px;">
                                <strong>Grade:</strong> 
                                <span style="font-size: 24px; font-weight: bold; color: <?php 
                                    echo ($average_score >= 90) ? '#28a745' : 
                                         (($average_score >= 80) ? '#17a2b8' : 
                                         (($average_score >= 70) ? '#ffc107' : 
                                         (($average_score >= 60) ? '#fd7e14' : '#dc3545')));
                                ?>;">
                                    <?php 
                                        if ($average_score >= 90) echo 'A';
                                        elseif ($average_score >= 80) echo 'B';
                                        elseif ($average_score >= 70) echo 'C';
                                        elseif ($average_score >= 60) echo 'D';
                                        else echo 'F';
                                    ?>
                                </span>
                            </p>
                        </div>
                        
                        <?php if ($class_average && $student_rank): ?>
                        <div style="padding: 20px;">
                            <h4 style="margin-bottom: 15px; color: #333;">Class Comparison</h4>
                            <div style="margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span>Class Average:</span>
                                    <strong><?php echo $class_average; ?>%</strong>
                                </div>
                                <div style="background: #f0f0f0; height: 8px; border-radius: 4px;">
                                    <div style="background: #17a2b8; height: 8px; border-radius: 4px; width: <?php echo min($class_average, 100); ?>%;"></div>
                                </div>
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span>My Score:</span>
                                    <strong style="color: <?php echo ($average_score > $class_average) ? '#28a745' : '#dc3545'; ?>;">
                                        <?php echo $average_score; ?>%
                                    </strong>
                                </div>
                                <div style="background: #f0f0f0; height: 8px; border-radius: 4px;">
                                    <div style="background: <?php echo ($average_score > $class_average) ? '#28a745' : '#dc3545'; ?>; height: 8px; border-radius: 4px; width: <?php echo min($average_score, 100); ?>%;"></div>
                                </div>
                            </div>
                            
                            <div style="text-align: center; padding: 15px; background: <?php echo ($average_score > $class_average) ? '#d4edda' : '#f8d7da'; ?>; border-radius: 8px; color: <?php echo ($average_score > $class_average) ? '#155724' : '#721c24'; ?>;">
                                <?php if ($average_score > $class_average): ?>
                                    🎉 Above Class Average by <?php echo round($average_score - $class_average, 1); ?>%
                                <?php else: ?>
                                    📈 <?php echo round($class_average - $average_score, 1); ?>% below class average
                                <?php endif; ?>
                            </div>
                            
                            <div style="text-align: center; margin-top: 15px;">
                                <strong>Class Rank: 
                                    <?php if ($student_rank == 1): ?>🥇<?php elseif ($student_rank == 2): ?>🥈<?php elseif ($student_rank == 3): ?>🥉<?php endif; ?>
                                    <?php echo $student_rank; ?> of <?php echo $total_students; ?>
                                </strong>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <h3>No Grades Yet</h3>
                        <p>Your grades will appear here once teachers enter them.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

<?php require_once 'footer.php'; ?>