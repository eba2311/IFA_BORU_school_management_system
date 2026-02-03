<?php
/**
 * ============================================
 * TEACHER DASHBOARD
 * ============================================
 */

$page_title = 'Teacher Dashboard';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$teacher_id = $_SESSION['teacher_id'];

// Get teacher statistics
try {
    $classes_count = $pdo->prepare("SELECT COUNT(*) FROM classes WHERE teacher_id = ?");
    $classes_count->execute([$teacher_id]);
    $classes_count = $classes_count->fetchColumn();

    $students_count = $pdo->prepare("
        SELECT COUNT(DISTINCT s.student_id) 
        FROM students s 
        JOIN classes c ON s.section_id = c.section_id 
        WHERE c.teacher_id = ?
    ");
    $students_count->execute([$teacher_id]);
    $students_count = $students_count->fetchColumn();

    $subjects_count = $pdo->prepare("
        SELECT COUNT(DISTINCT c.subject_id) 
        FROM classes c 
        WHERE c.teacher_id = ?
    ");
    $subjects_count->execute([$teacher_id]);
    $subjects_count = $subjects_count->fetchColumn();

    // Get class averages for teacher's classes
    $class_averages = $pdo->prepare("
        SELECT c.class_id, s.subject_name, sec.section_name, g.grade_level,
               AVG(sg.total_score) as class_average,
               COUNT(sg.student_id) as graded_students
        FROM classes c
        JOIN subjects s ON c.subject_id = s.subject_id
        JOIN sections sec ON c.section_id = sec.section_id
        JOIN grades g ON sec.grade_id = g.grade_id
        LEFT JOIN student_grades sg ON c.class_id = sg.class_id
        WHERE c.teacher_id = ?
        GROUP BY c.class_id
        ORDER BY g.grade_level, sec.section_name, s.subject_name
    ");
    $class_averages->execute([$teacher_id]);
    $class_averages = $class_averages->fetchAll();

} catch (Exception $e) {
    $classes_count = $students_count = $subjects_count = 0;
    $class_averages = [];
}
?>

        <div class="container">
            <h1>Welcome, <?php echo $_SESSION['teacher_name']; ?>! 👋</h1>
            <p style="color: #999; margin-bottom: 30px;">Here's your teaching overview</p>

            <div class="stats">
                <div class="stat-card" style="border-left-color: #28a745;">
                    <h3>My Classes</h3>
                    <div class="stat-number"><?php echo $classes_count; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #17a2b8;">
                    <h3>My Students</h3>
                    <div class="stat-number"><?php echo $students_count; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #ffc107;">
                    <h3>Subjects Teaching</h3>
                    <div class="stat-number"><?php echo $subjects_count; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #dc3545;">
                    <h3>Academic Year</h3>
                    <div class="stat-number">2026</div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">Quick Actions</div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                    <a href="classes.php" class="btn">🎓 View My Classes</a>
                    <a href="grades.php" class="btn">📝 Enter Grades</a>
                    <a href="classes.php" class="btn btn-secondary">👨‍🎓 View Students</a>
                </div>
            </div>

            <div class="card">
                <div class="card-title">My Classes</div>
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Grade</th>
                            <th>Section</th>
                            <th>Students</th>
                            <th>Graded Students</th>
                            <th>Class Average</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $query = "SELECT c.*, s.subject_name, sec.section_name, g.grade_level,
                                             COUNT(st.student_id) as student_count
                                      FROM classes c
                                      JOIN subjects s ON c.subject_id = s.subject_id
                                      JOIN sections sec ON c.section_id = sec.section_id
                                      JOIN grades g ON sec.grade_id = g.grade_id
                                      LEFT JOIN students st ON sec.section_id = st.section_id AND st.status = 'Active'
                                      WHERE c.teacher_id = ?
                                      GROUP BY c.class_id
                                      ORDER BY g.grade_level, sec.section_name, s.subject_name";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute([$teacher_id]);
                            $classes = $stmt->fetchAll();
                            
                            if (count($classes) > 0) {
                                foreach ($classes as $class) {
                                    // Find matching class average data
                                    $avg_data = null;
                                    foreach ($class_averages as $avg) {
                                        if ($avg['class_id'] == $class['class_id']) {
                                            $avg_data = $avg;
                                            break;
                                        }
                                    }
                                    
                                    $class_avg = $avg_data ? round($avg_data['class_average'], 1) : null;
                                    $graded_count = $avg_data ? $avg_data['graded_students'] : 0;
                                    
                                    // Determine performance color
                                    $perf_color = '#999';
                                    $performance = 'No Grades';
                                    if ($class_avg !== null) {
                                        if ($class_avg >= 85) {
                                            $perf_color = '#28a745';
                                            $performance = 'Excellent';
                                        } elseif ($class_avg >= 75) {
                                            $perf_color = '#17a2b8';
                                            $performance = 'Good';
                                        } elseif ($class_avg >= 65) {
                                            $perf_color = '#ffc107';
                                            $performance = 'Average';
                                        } else {
                                            $perf_color = '#dc3545';
                                            $performance = 'Needs Attention';
                                        }
                                    }
                                    
                                    echo "<tr>
                                        <td><strong>{$class['subject_name']}</strong></td>
                                        <td>Grade {$class['grade_level']}</td>
                                        <td>Section {$class['section_name']}</td>
                                        <td>{$class['student_count']} students</td>
                                        <td style='text-align: center;'>{$graded_count}</td>
                                        <td style='text-align: center; font-weight: bold;'>" . 
                                        ($class_avg ? $class_avg . '%' : 'N/A') . "</td>
                                        <td style='text-align: center; color: {$perf_color}; font-weight: 600;'>{$performance}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align: center; color: #999;'>No classes assigned yet. Contact admin to assign classes.</td></tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='7' style='text-align: center; color: #e74c3c;'>Error loading classes</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-title">Class Performance Overview</div>
                <?php if (!empty($class_averages) && array_filter($class_averages, function($c) { return $c['class_average'] !== null; })): ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <?php foreach ($class_averages as $class): ?>
                            <?php if ($class['class_average'] !== null): ?>
                                <?php
                                $avg = round($class['class_average'], 1);
                                $color = '#dc3545'; // Default red
                                $status = 'Needs Attention';
                                
                                if ($avg >= 85) {
                                    $color = '#28a745';
                                    $status = 'Excellent';
                                } elseif ($avg >= 75) {
                                    $color = '#17a2b8';
                                    $status = 'Good';
                                } elseif ($avg >= 65) {
                                    $color = '#ffc107';
                                    $status = 'Average';
                                }
                                ?>
                                <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; background: #f9f9f9;">
                                    <h4 style="margin-bottom: 10px; color: #333;">
                                        <?php echo $class['subject_name']; ?>
                                    </h4>
                                    <p style="color: #666; margin-bottom: 15px; font-size: 14px;">
                                        Grade <?php echo $class['grade_level']; ?> - Section <?php echo $class['section_name']; ?>
                                    </p>
                                    
                                    <div style="text-align: center; margin-bottom: 15px;">
                                        <div style="font-size: 32px; font-weight: bold; color: <?php echo $color; ?>;">
                                            <?php echo $avg; ?>%
                                        </div>
                                        <div style="color: <?php echo $color; ?>; font-weight: 600; font-size: 14px;">
                                            <?php echo $status; ?>
                                        </div>
                                    </div>
                                    
                                    <div style="background: #f0f0f0; height: 8px; border-radius: 4px; margin-bottom: 10px;">
                                        <div style="background: <?php echo $color; ?>; height: 8px; border-radius: 4px; width: <?php echo min($avg, 100); ?>%;"></div>
                                    </div>
                                    
                                    <div style="display: flex; justify-content: space-between; font-size: 12px; color: #666;">
                                        <span>Graded: <?php echo $class['graded_students']; ?></span>
                                        <span>Class Average</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <h3>No Class Data Available</h3>
                        <p>Class averages will appear here once you enter student grades.</p>
                        <a href="grades.php" class="btn" style="margin-top: 15px;">📝 Enter Grades</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card">
                <div class="card-title">Teacher Information</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <p><strong>Teacher Code:</strong> <?php echo $_SESSION['teacher_code'] ?? 'N/A'; ?></p>
                        <p><strong>Full Name:</strong> <?php echo $_SESSION['teacher_name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $_SESSION['teacher_email']; ?></p>
                    </div>
                    <div>
                        <p><strong>Username:</strong> <?php echo $_SESSION['teacher_username']; ?></p>
                        <p><strong>Login Time:</strong> <?php echo date('Y-m-d H:i:s', $_SESSION['login_time']); ?></p>
                        <p><strong>Status:</strong> <span style="color: #28a745; font-weight: 600;">Active</span></p>
                    </div>
                </div>
            </div>
        </div>

<?php require_once 'footer.php'; ?>