<?php
/**
 * ============================================
 * ADMIN - STUDENT RANKINGS
 * ============================================
 */

$page_title = 'Student Rankings';
require_once 'header.php';
require_once __DIR__ . '/../config/Database.php';

// Initialize database connection
$db = new Database();
$pdo = $db->connect();

$selected_grade = isset($_GET['grade_id']) ? (int)$_GET['grade_id'] : 0;
$selected_section = isset($_GET['section_id']) ? (int)$_GET['section_id'] : 0;

// Get grades and sections
$grades = $pdo->query("SELECT * FROM grades ORDER BY grade_level")->fetchAll();
$sections = [];
if ($selected_grade > 0) {
    $sections_query = $pdo->prepare("SELECT * FROM sections WHERE grade_id = ? ORDER BY section_name");
    $sections_query->execute([$selected_grade]);
    $sections = $sections_query->fetchAll();
}
?>

        <div class="container">
            <h1>🏆 Student Rankings</h1>
            <p style="color: #999; margin-bottom: 30px;">View student performance rankings by grade and section</p>

            <div class="card">
                <div class="card-title">Filter Rankings</div>
                <form method="GET" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="grade_id">Grade:</label>
                        <select id="grade_id" name="grade_id" onchange="loadSections(this.value)">
                            <option value="">All Grades</option>
                            <?php foreach ($grades as $grade): ?>
                                <option value="<?php echo $grade['grade_id']; ?>" 
                                        <?php echo ($selected_grade == $grade['grade_id']) ? 'selected' : ''; ?>>
                                    Grade <?php echo $grade['grade_level']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="section_id">Section:</label>
                        <select id="section_id" name="section_id">
                            <option value="">All Sections</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?php echo $section['section_id']; ?>"
                                        <?php echo ($selected_section == $section['section_id']) ? 'selected' : ''; ?>>
                                    Section <?php echo $section['section_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn">🔍 View Rankings</button>
                </form>
            </div>

            <?php
            // Build query based on filters
            $where_conditions = [];
            $params = [];
            
            if ($selected_grade > 0) {
                $where_conditions[] = "s.grade_id = ?";
                $params[] = $selected_grade;
            }
            
            if ($selected_section > 0) {
                $where_conditions[] = "s.section_id = ?";
                $params[] = $selected_section;
            }
            
            $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";
            
            try {
                // Get student rankings with average scores
                $rankings_query = "
                    SELECT s.student_id, s.student_code, s.full_name, g.grade_level, sec.section_name,
                           AVG(sg.total_score) as average_score,
                           COUNT(sg.grade_id) as total_subjects,
                           RANK() OVER (ORDER BY AVG(sg.total_score) DESC) as student_rank
                    FROM students s
                    JOIN grades g ON s.grade_id = g.grade_id
                    JOIN sections sec ON s.section_id = sec.section_id
                    LEFT JOIN student_grades sg ON s.student_id = sg.student_id
                    $where_clause
                    GROUP BY s.student_id
                    HAVING AVG(sg.total_score) IS NOT NULL
                    ORDER BY average_score DESC
                ";
                
                $rankings_stmt = $pdo->prepare($rankings_query);
                $rankings_stmt->execute($params);
                $rankings = $rankings_stmt->fetchAll();
                
                // Calculate class statistics
                if (!empty($rankings)) {
                    $total_students = count($rankings);
                    $class_average = array_sum(array_column($rankings, 'average_score')) / $total_students;
                    $highest_score = max(array_column($rankings, 'average_score'));
                    $lowest_score = min(array_column($rankings, 'average_score'));
                }
            } catch (Exception $e) {
                $rankings = [];
                $total_students = $class_average = $highest_score = $lowest_score = 0;
            }
            ?>

            <?php if (!empty($rankings)): ?>
            <div class="stats">
                <div class="stat-card" style="border-left-color: #28a745;">
                    <h3>Total Students</h3>
                    <div class="stat-number"><?php echo $total_students; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #17a2b8;">
                    <h3>Class Average</h3>
                    <div class="stat-number"><?php echo round($class_average, 1); ?>%</div>
                </div>
                <div class="stat-card" style="border-left-color: #ffc107;">
                    <h3>Highest Score</h3>
                    <div class="stat-number"><?php echo round($highest_score, 1); ?>%</div>
                </div>
                <div class="stat-card" style="border-left-color: #dc3545;">
                    <h3>Lowest Score</h3>
                    <div class="stat-number"><?php echo round($lowest_score, 1); ?>%</div>
                </div>
            </div>

            <div class="card">
                <div class="card-title">
                    Student Rankings 
                    <?php if ($selected_grade > 0 || $selected_section > 0): ?>
                        - 
                        <?php if ($selected_grade > 0): ?>
                            Grade <?php echo $grades[array_search($selected_grade, array_column($grades, 'grade_id'))]['grade_level']; ?>
                        <?php endif; ?>
                        <?php if ($selected_section > 0): ?>
                            Section <?php echo $sections[array_search($selected_section, array_column($sections, 'section_id'))]['section_name']; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Student Code</th>
                            <th>Full Name</th>
                            <th>Grade</th>
                            <th>Section</th>
                            <th>Subjects</th>
                            <th>Average Score</th>
                            <th>Grade Letter</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rankings as $index => $student): ?>
                            <?php
                            $rank = $student['student_rank'];
                            $average = round($student['average_score'], 1);
                            
                            // Determine grade letter and color
                            $grade_letter = 'F';
                            $grade_color = '#dc3545';
                            $performance = 'Needs Improvement';
                            
                            if ($average >= 90) {
                                $grade_letter = 'A';
                                $grade_color = '#28a745';
                                $performance = 'Excellent';
                            } elseif ($average >= 80) {
                                $grade_letter = 'B';
                                $grade_color = '#17a2b8';
                                $performance = 'Good';
                            } elseif ($average >= 70) {
                                $grade_letter = 'C';
                                $grade_color = '#ffc107';
                                $performance = 'Satisfactory';
                            } elseif ($average >= 60) {
                                $grade_letter = 'D';
                                $grade_color = '#fd7e14';
                                $performance = 'Pass';
                            }
                            
                            // Rank styling
                            $rank_style = '';
                            if ($rank == 1) $rank_style = 'background: #ffd700; color: #333; font-weight: bold;'; // Gold
                            elseif ($rank == 2) $rank_style = 'background: #c0c0c0; color: #333; font-weight: bold;'; // Silver
                            elseif ($rank == 3) $rank_style = 'background: #cd7f32; color: white; font-weight: bold;'; // Bronze
                            ?>
                            <tr>
                                <td style="text-align: center; <?php echo $rank_style; ?> padding: 8px; border-radius: 5px;">
                                    <?php if ($rank == 1): ?>
                                        🥇 <?php echo $rank; ?>
                                    <?php elseif ($rank == 2): ?>
                                        🥈 <?php echo $rank; ?>
                                    <?php elseif ($rank == 3): ?>
                                        🥉 <?php echo $rank; ?>
                                    <?php else: ?>
                                        <?php echo $rank; ?>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo $student['student_code']; ?></strong></td>
                                <td><?php echo $student['full_name']; ?></td>
                                <td>Grade <?php echo $student['grade_level']; ?></td>
                                <td>Section <?php echo $student['section_name']; ?></td>
                                <td style="text-align: center;"><?php echo $student['total_subjects']; ?></td>
                                <td style="text-align: center; font-weight: bold; font-size: 16px;"><?php echo $average; ?>%</td>
                                <td style="text-align: center;">
                                    <span style="font-size: 18px; font-weight: bold; color: <?php echo $grade_color; ?>;">
                                        <?php echo $grade_letter; ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="color: <?php echo $grade_color; ?>; font-weight: 600; font-size: 12px;">
                                        <?php echo $performance; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <div class="card-title">Performance Distribution</div>
                <?php
                $grade_distribution = [
                    'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0
                ];
                
                foreach ($rankings as $student) {
                    $avg = $student['average_score'];
                    if ($avg >= 90) $grade_distribution['A']++;
                    elseif ($avg >= 80) $grade_distribution['B']++;
                    elseif ($avg >= 70) $grade_distribution['C']++;
                    elseif ($avg >= 60) $grade_distribution['D']++;
                    else $grade_distribution['F']++;
                }
                ?>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px;">
                    <div style="text-align: center; padding: 15px; background: #28a745; color: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold;">A</div>
                        <div style="font-size: 18px;"><?php echo $grade_distribution['A']; ?></div>
                        <div style="font-size: 12px; opacity: 0.9;">Excellent</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #17a2b8; color: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold;">B</div>
                        <div style="font-size: 18px;"><?php echo $grade_distribution['B']; ?></div>
                        <div style="font-size: 12px; opacity: 0.9;">Good</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #ffc107; color: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold;">C</div>
                        <div style="font-size: 18px;"><?php echo $grade_distribution['C']; ?></div>
                        <div style="font-size: 12px; opacity: 0.9;">Satisfactory</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #fd7e14; color: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold;">D</div>
                        <div style="font-size: 18px;"><?php echo $grade_distribution['D']; ?></div>
                        <div style="font-size: 12px; opacity: 0.9;">Pass</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: #dc3545; color: white; border-radius: 8px;">
                        <div style="font-size: 24px; font-weight: bold;">F</div>
                        <div style="font-size: 18px;"><?php echo $grade_distribution['F']; ?></div>
                        <div style="font-size: 12px; opacity: 0.9;">Fail</div>
                    </div>
                </div>
            </div>

            <?php else: ?>
            <div class="card">
                <div style="text-align: center; padding: 40px; color: #666;">
                    <h3>No Rankings Available</h3>
                    <p>No student grades found for the selected criteria.</p>
                    <p>Make sure students have grades entered by teachers.</p>
                </div>
            </div>
            <?php endif; ?>

            <div style="margin-top: 20px; text-align: center;">
                <button onclick="window.print()" class="btn">🖨️ Print Rankings</button>
            </div>
        </div>

        <script>
            function loadSections(gradeId) {
                if (gradeId) {
                    fetch('get_sections.php?grade_id=' + gradeId)
                        .then(response => response.json())
                        .then(data => {
                            const select = document.getElementById('section_id');
                            select.innerHTML = '<option value="">All Sections</option>';
                            data.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.section_id;
                                option.textContent = 'Section ' + section.section_name;
                                select.appendChild(option);
                            });
                        });
                } else {
                    document.getElementById('section_id').innerHTML = '<option value="">All Sections</option>';
                }
            }
        </script>

<?php require_once 'footer.php'; ?>