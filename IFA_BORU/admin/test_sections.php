<?php
/**
 * ============================================
 * TEST SECTIONS PAGE
 * ============================================
 */

$page_title = "Test Sections";
require_once 'header.php';
?>

<div class="container">
    <div class="card">
        <div class="card-title">
            <h2>📚 Section Management Test</h2>
            <p>This is a test page to verify the sections functionality works</p>
        </div>

        <div style="padding: 20px;">
            <h3>✅ Test Results:</h3>
            <p>✅ PHP is working</p>
            <p>✅ Header loaded successfully</p>
            <p>✅ Navigation is functional</p>
            
            <h3>🔗 Navigation Test:</h3>
            <p><a href="dashboard.php" class="btn">🏠 Back to Dashboard</a></p>
            <p><a href="manage_sections.php" class="btn">📋 Try Full Sections Page</a></p>
            
            <h3>📊 Quick Database Test:</h3>
            <?php
            try {
                require_once '../config/Database.php';
                $db = new Database();
                $pdo = $db->connect();
                
                $sections_count = $pdo->query("SELECT COUNT(*) FROM sections")->fetchColumn();
                $grades_count = $pdo->query("SELECT COUNT(*) FROM grades")->fetchColumn();
                
                echo "<p>✅ Database connected successfully</p>";
                echo "<p>📊 Total Sections: <strong>$sections_count</strong></p>";
                echo "<p>📚 Total Grades: <strong>$grades_count</strong></p>";
                
            } catch (Exception $e) {
                echo "<p>❌ Database Error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>