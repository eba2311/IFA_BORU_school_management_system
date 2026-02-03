<?php
/**
 * ============================================
 * STUDENT GRADES
 * ============================================
 */

$page_title = 'My Grades';
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

} catch (Exception $e) {
    $student_info = null;
}
?>

        <div class="container">
            <h1>📝 My Grades</h1>
            <p style="color: #999; margin-bottom: 30px;">View all your academic grades and performance</p>

            <?php if ($student_info): ?>
            <div class="card">
                <div class="card-title">Academic Summary</div>
                <div class="profile-info">
                    <div>
                        <p><strong>Student:</strong> <?php echo $student_info['full_name']; ?></p>
                        <p><strong>Student Code:</strong> <?php echo $student_info['student_code']; ?></p>
                        <p><strong>Grade & Section:</strong> Grade <?php echo $student_info['grade_level']; ?> - Section <?php echo $student_info['section_name']; ?></p>
                    </div>
                    <div>
                        <?php
                        // Calculate overall statistics
                        try {
                            $average_score = $pdo->prepare("SELECT AVG(total_score) FROM student_grades WHERE student_id = ?");
                            $average_score->execute([$student_id]);
                            $average_score = round($average_score->fetchColumn(), 1);

                            $total_grades = $pdo->prepare("SELECT COUNT(*) FROM student_grades WHERE student_id = ?");
                            $total_grades->execute([$student_id]);
                            $total_grades = $total_grades->fetchColumn();

                            $highest_score = $pdo->prepare("SELECT MAX(total_score) FROM student_grades WHERE student_id = ?");
                            $highest_score->execute([$student_id]);
                            $highest_score = $highest_score->fetchColumn();

                            $lowest_score = $pdo->prepare("SELECT MIN(total_score) FROM student_grades WHERE student_id = ?");
                            $lowest_score->execute([$student_id]);
                            $lowest_score = $lowest_score->fetchColumn();
                        } catch (Exception $e) {
                            $average_score = $total_grades = $highest_score = $lowest_score = 0;
                        }
                        ?>
                        <p><strong>Total Subjects:</strong> <?php echo $total_grades; ?></p>
                        <p><strong>Average Score:</strong> <?php echo $average_score ?: '0'; ?>%</p>
                        <p><strong>Highest Score:</strong> <?php echo $highest_score ?: '0'; ?>%</p>
                        <p><strong>Lowest Score:</strong> <?php echo $lowest_score ?: '0'; ?>%</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Overall Performance</div>
                <div style="text-align: center; padding: 30px;">
                    <div style="display: inline-block; padding: 30px; background: linear-gradient(135deg, #17a2b8, #007bff); border-radius: 15px; color: white;">
                        <div style="font-size: 64px; font-weight: bold; margin-bottom: 10px;">
                            <?php echo $average_score ?: '0'; ?>%
                        </div>
                        <div style="font-size: 18px; margin-bottom: 10px;">Overall Average</div>
                        <div style="font-size: 32px; font-weight: bold;">
                            <?php 
                                if ($average_score >= 90) echo 'A';
                                elseif ($average_score >= 80) echo 'B';
                                elseif ($average_score >= 70) echo 'C';
                                elseif ($average_score >= 60) echo 'D';
                                elseif ($average_score > 0) echo 'F';
                                else echo '-';
                            ?>
                        </div>
                        <div style="font-size: 14px; opacity: 0.9;">
                            <?php 
                                if ($average_score >= 90) echo 'Excellent Performance';
                                elseif ($average_score >= 80) echo 'Good Performance';
                                elseif ($average_score >= 70) echo 'Satisfactory Performance';
                                elseif ($average_score >= 60) echo 'Pass';
                                elseif ($average_score > 0) echo 'Needs Improvement';
                                else echo 'No grades yet';
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Detailed Grades</div>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Assignment<br><small>(10 pts)</small></th>
                                <th>Test<br><small>(10 pts)</small></th>
                                <th>Mid Exam<br><small>(20 pts)</small></th>
                                <th>Final Exam<br><small>(60 pts)</small></th>
                                <th>Total<br><small>(100 pts)</small></th>
                                <th>Grade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $grades_query = "SELECT sg.*, s.subject_name, s.subject_code
                                                FROM student_grades sg
                                                JOIN classes c ON sg.class_id = c.class_id
                                                JOIN subjects s ON c.subject_id = s.subject_id
                                                WHERE sg.student_id = ?
                                                ORDER BY s.subject_name";
                                $grades_stmt = $pdo->prepare($grades_query);
                                $grades_stmt->execute([$student_id]);
                                $grades = $grades_stmt->fetchAll();
                                
                                if (count($grades) > 0) {
                                    foreach ($grades as $grade) {
                                        $grade_color = '#dc3545'; // F - Red
                                        $status = 'Fail';
                                        if ($grade['grade_letter'] === 'A') {
                                            $grade_color = '#28a745'; // Green
                                            $status = 'Excellent';
                                        } elseif ($grade['grade_letter'] === 'B') {
                                            $grade_color = '#17a2b8'; // Blue
                                            $status = 'Good';
                                        } elseif ($grade['grade_letter'] === 'C') {
                                            $grade_color = '#ffc107'; // Yellow
                                            $status = 'Satisfactory';
                                        } elseif ($grade['grade_letter'] === 'D') {
                                            $grade_color = '#fd7e14'; // Orange
                                            $status = 'Pass';
                                        }
                                        
                                        echo "<tr>
                                            <td>
                                                <strong>{$grade['subject_name']}</strong><br>
                                                <small style='color: #666;'>{$grade['subject_code']}</small>
                                            </td>
                                            <td style='text-align: center;'>{$grade['assignment_score']}</td>
                                            <td style='text-align: center;'>{$grade['test_score']}</td>
                                            <td style='text-align: center;'>{$grade['mid_exam_score']}</td>
                                            <td style='text-align: center;'>{$grade['final_exam_score']}</td>
                                            <td style='text-align: center;'><strong>{$grade['total_score']}</strong></td>
                                            <td style='text-align: center;'>
                                                <span style='font-size: 18px; font-weight: bold; color: {$grade_color};'>
                                                    {$grade['grade_letter']}
                                                </span>
                                            </td>
                                            <td style='text-align: center;'>
                                                <span style='color: {$grade_color}; font-weight: 600; font-size: 12px;'>
                                                    {$status}
                                                </span>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' style='text-align: center; color: #999; padding: 40px;'>
                                            <h4>No Grades Available</h4>
                                            <p>Your grades will appear here once teachers enter them.</p>
                                          </td></tr>";
                                }
                            } catch (Exception $e) {
                                echo "<tr><td colspan='8' style='text-align: center; color: #e74c3c; padding: 40px;'>
                                        <h4>Error Loading Grades</h4>
                                        <p>Unable to load your grades. Please try again later.</p>
                                      </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Grading Scale</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <div style="text-align: center; padding: 15px; background: #28a745; color: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold;">A</div>
                        <div style="font-size: 14px;">90-100</div>
                        <div style="font-size: 12px; opacity: 0.9;">Excellent</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #17a2b8; color: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold;">B</div>
                        <div style="font-size: 14px;">80-89</div>
                        <div style="font-size: 12px; opacity: 0.9;">Good</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #ffc107; color: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold;">C</div>
                        <div style="font-size: 14px;">70-79</div>
                        <div style="font-size: 12px; opacity: 0.9;">Satisfactory</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #fd7e14; color: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold;">D</div>
                        <div style="font-size: 14px;">60-69</div>
                        <div style="font-size: 12px; opacity: 0.9;">Pass</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #dc3545; color: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold;">F</div>
                        <div style="font-size: 14px;">0-59</div>
                        <div style="font-size: 12px; opacity: 0.9;">Fail</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Actions</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                    <a href="profile.php" class="btn btn-secondary">👤 View Profile</a>
                    <a href="dashboard.php" class="btn btn-secondary">📊 Go to Dashboard</a>
                    <button onclick="window.print()" class="btn">🖨️ Print Grades</button>
                </div>
            </div>

            <?php else: ?>
            <div class="card">
                <div style="text-align: center; padding: 40px; color: #e74c3c;">
                    <h3>Student Information Not Found</h3>
                    <p>Unable to load your information. Please contact the administrator.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

<?php require_once 'footer.php'; ?>