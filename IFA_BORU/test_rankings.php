<?php
/**
 * ============================================
 * TEST RANKINGS SYSTEM
 * ============================================
 */

require_once 'config/Database.php';

echo "<h1>🏆 Testing Ranking System</h1>";

try {
    $db = new Database();
    $pdo = $db->connect();
    
    echo "<h2>📊 Current Student Grades</h2>";
    
    // Check if we have any student grades
    $grades_query = "
        SELECT s.student_code, s.full_name, g.grade_level, sec.section_name,
               AVG(sg.total_score) as average_score,
               COUNT(sg.grade_id) as total_subjects
        FROM students s
        JOIN grades g ON s.grade_id = g.grade_id
        JOIN sections sec ON s.section_id = sec.section_id
        LEFT JOIN student_grades sg ON s.student_id = sg.student_id
        GROUP BY s.student_id
        ORDER BY average_score DESC
    ";
    
    $stmt = $pdo->prepare($grades_query);
    $stmt->execute();
    $students = $stmt->fetchAll();
    
    if (empty($students)) {
        echo "<p style='color: red;'>❌ No students found in database</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>
                <th>Student Code</th>
                <th>Name</th>
                <th>Grade</th>
                <th>Section</th>
                <th>Subjects</th>
                <th>Average</th>
              </tr>";
        
        foreach ($students as $student) {
            $avg = $student['average_score'] ? round($student['average_score'], 1) : 'N/A';
            echo "<tr>
                    <td>{$student['student_code']}</td>
                    <td>{$student['full_name']}</td>
                    <td>Grade {$student['grade_level']}</td>
                    <td>Section {$student['section_name']}</td>
                    <td>{$student['total_subjects']}</td>
                    <td>{$avg}</td>
                  </tr>";
        }
        echo "</table>";
    }
    
    echo "<h2>🎯 Sample Data Creation</h2>";
    
    // Check if we need to create sample grades
    $sample_check = $pdo->query("SELECT COUNT(*) FROM student_grades")->fetchColumn();
    
    if ($sample_check == 0) {
        echo "<p>Creating sample grades for testing...</p>";
        
        // Get first few students and classes
        $students = $pdo->query("SELECT student_id FROM students LIMIT 5")->fetchAll();
        $classes = $pdo->query("SELECT class_id FROM classes LIMIT 3")->fetchAll();
        
        if (!empty($students) && !empty($classes)) {
            foreach ($students as $student) {
                foreach ($classes as $class) {
                    // Generate random but realistic grades
                    $assignment = rand(70, 95);
                    $test = rand(65, 90);
                    $mid_exam = rand(60, 85);
                    $final_exam = rand(65, 90);
                    $total = round(($assignment + $test + $mid_exam + $final_exam) / 4, 1);
                    
                    // Determine grade letter
                    $grade_letter = 'F';
                    if ($total >= 90) $grade_letter = 'A';
                    elseif ($total >= 80) $grade_letter = 'B';
                    elseif ($total >= 70) $grade_letter = 'C';
                    elseif ($total >= 60) $grade_letter = 'D';
                    
                    $insert_query = "
                        INSERT INTO student_grades 
                        (student_id, class_id, assignment_score, test_score, mid_exam_score, final_exam_score, total_score, grade_letter)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ";
                    
                    $stmt = $pdo->prepare($insert_query);
                    $stmt->execute([
                        $student['student_id'],
                        $class['class_id'],
                        $assignment,
                        $test,
                        $mid_exam,
                        $final_exam,
                        $total,
                        $grade_letter
                    ]);
                }
            }
            echo "<p style='color: green;'>✅ Sample grades created successfully!</p>";
        } else {
            echo "<p style='color: red;'>❌ No students or classes found to create sample data</p>";
        }
    } else {
        echo "<p style='color: blue;'>ℹ️ Sample grades already exist ({$sample_check} records)</p>";
    }
    
    echo "<h2>🔗 Test Links</h2>";
    echo "<ul>";
    echo "<li><a href='admin/rankings.php' target='_blank'>📊 Admin Rankings Page</a></li>";
    echo "<li><a href='student/dashboard.php' target='_blank'>👨‍🎓 Student Dashboard (with rank)</a></li>";
    echo "<li><a href='teacher/dashboard.php' target='_blank'>👨‍🏫 Teacher Dashboard (with class averages)</a></li>";
    echo "<li><a href='index.php' target='_blank'>🏠 Login Page</a></li>";
    echo "</ul>";
    
    echo "<h2>📋 Login Credentials</h2>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> username: admin, password: admin123</li>";
    echo "<li><strong>Teacher:</strong> username: sarahsmith, password: teacher123</li>";
    echo "<li><strong>Student:</strong> Student Code: STU20260001, DOB: 2005-01-15</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin: 20px 0; }
th, td { padding: 8px 12px; text-align: left; }
th { background: #f0f0f0; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>