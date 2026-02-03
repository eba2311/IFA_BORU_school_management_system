<?php
/**
 * ============================================
 * SECTION MANAGER CLASS
 * ============================================
 * Helper class for managing sections dynamically
 */

class SectionManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get all sections for a specific grade
     */
    public function getSectionsByGrade($grade_id) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, 
                   (SELECT COUNT(*) FROM students WHERE section_id = s.section_id) as student_count
            FROM sections s 
            WHERE s.grade_id = ? 
            ORDER BY s.section_name
        ");
        $stmt->execute([$grade_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all sections with grade information
     */
    public function getAllSections() {
        $stmt = $this->pdo->query("
            SELECT s.*, g.grade_level, g.grade_name,
                   (SELECT COUNT(*) FROM students WHERE section_id = s.section_id) as student_count
            FROM sections s 
            JOIN grades g ON s.grade_id = g.grade_id 
            ORDER BY g.grade_level, s.section_name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get section by ID
     */
    public function getSectionById($section_id) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, g.grade_level, g.grade_name,
                   (SELECT COUNT(*) FROM students WHERE section_id = s.section_id) as student_count
            FROM sections s 
            JOIN grades g ON s.grade_id = g.grade_id 
            WHERE s.section_id = ?
        ");
        $stmt->execute([$section_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Add a new section
     */
    public function addSection($grade_id, $section_name, $max_students = 50) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO sections (grade_id, section_name, max_students) VALUES (?, ?, ?)");
            return $stmt->execute([$grade_id, $section_name, $max_students]);
        } catch (PDOException $e) {
            throw new Exception("Error adding section: " . $e->getMessage());
        }
    }
    
    /**
     * Update section
     */
    public function updateSection($section_id, $section_name, $max_students) {
        try {
            $stmt = $this->pdo->prepare("UPDATE sections SET section_name = ?, max_students = ? WHERE section_id = ?");
            return $stmt->execute([$section_name, $max_students, $section_id]);
        } catch (PDOException $e) {
            throw new Exception("Error updating section: " . $e->getMessage());
        }
    }
    
    /**
     * Delete section (only if no students are enrolled)
     */
    public function deleteSection($section_id) {
        try {
            // Check if section has students
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM students WHERE section_id = ?");
            $stmt->execute([$section_id]);
            $student_count = $stmt->fetchColumn();
            
            if ($student_count > 0) {
                throw new Exception("Cannot delete section! It has $student_count students enrolled.");
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM sections WHERE section_id = ?");
            return $stmt->execute([$section_id]);
        } catch (PDOException $e) {
            throw new Exception("Error deleting section: " . $e->getMessage());
        }
    }
    
    /**
     * Generate alphabetic sections (A, B, C, ...)
     */
    public function generateAlphabeticSections($grade_id, $count, $max_students = 50) {
        $sections_added = 0;
        $sections_failed = 0;
        
        for ($i = 0; $i < $count; $i++) {
            $section_name = chr(65 + $i); // A, B, C, D...
            try {
                $this->addSection($grade_id, $section_name, $max_students);
                $sections_added++;
            } catch (Exception $e) {
                $sections_failed++;
            }
        }
        
        return ['added' => $sections_added, 'failed' => $sections_failed];
    }
    
    /**
     * Generate numeric sections (1, 2, 3, ...)
     */
    public function generateNumericSections($grade_id, $count, $max_students = 50) {
        $sections_added = 0;
        $sections_failed = 0;
        
        for ($i = 1; $i <= $count; $i++) {
            $section_name = (string)$i;
            try {
                $this->addSection($grade_id, $section_name, $max_students);
                $sections_added++;
            } catch (Exception $e) {
                $sections_failed++;
            }
        }
        
        return ['added' => $sections_added, 'failed' => $sections_failed];
    }
    
    /**
     * Add custom sections from array
     */
    public function addCustomSections($grade_id, $section_names, $max_students = 50) {
        $sections_added = 0;
        $sections_failed = 0;
        
        foreach ($section_names as $section_name) {
            $section_name = trim($section_name);
            if (!empty($section_name)) {
                try {
                    $this->addSection($grade_id, $section_name, $max_students);
                    $sections_added++;
                } catch (Exception $e) {
                    $sections_failed++;
                }
            }
        }
        
        return ['added' => $sections_added, 'failed' => $sections_failed];
    }
    
    /**
     * Get available sections for student enrollment
     */
    public function getAvailableSections($grade_id) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, 
                   (SELECT COUNT(*) FROM students WHERE section_id = s.section_id) as student_count,
                   (s.max_students - (SELECT COUNT(*) FROM students WHERE section_id = s.section_id)) as available_spots
            FROM sections s 
            WHERE s.grade_id = ? 
            HAVING available_spots > 0
            ORDER BY s.section_name
        ");
        $stmt->execute([$grade_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get section statistics
     */
    public function getSectionStats($section_id) {
        $stmt = $this->pdo->prepare("
            SELECT 
                s.*,
                g.grade_level,
                g.grade_name,
                COUNT(st.student_id) as total_students,
                COUNT(CASE WHEN st.gender = 'Male' THEN 1 END) as male_students,
                COUNT(CASE WHEN st.gender = 'Female' THEN 1 END) as female_students,
                (s.max_students - COUNT(st.student_id)) as available_spots
            FROM sections s
            JOIN grades g ON s.grade_id = g.grade_id
            LEFT JOIN students st ON s.section_id = st.section_id AND st.status = 'Active'
            WHERE s.section_id = ?
            GROUP BY s.section_id
        ");
        $stmt->execute([$section_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Check if section name exists for a grade
     */
    public function sectionExists($grade_id, $section_name, $exclude_section_id = null) {
        $sql = "SELECT COUNT(*) FROM sections WHERE grade_id = ? AND section_name = ?";
        $params = [$grade_id, $section_name];
        
        if ($exclude_section_id) {
            $sql .= " AND section_id != ?";
            $params[] = $exclude_section_id;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Get sections dropdown options for forms
     */
    public function getSectionOptions($grade_id = null, $include_student_count = false) {
        $sql = "
            SELECT s.section_id, s.section_name, g.grade_level, g.grade_name";
        
        if ($include_student_count) {
            $sql .= ", (SELECT COUNT(*) FROM students WHERE section_id = s.section_id) as student_count";
        }
        
        $sql .= " FROM sections s 
                  JOIN grades g ON s.grade_id = g.grade_id";
        
        if ($grade_id) {
            $sql .= " WHERE s.grade_id = ?";
        }
        
        $sql .= " ORDER BY g.grade_level, s.section_name";
        
        $stmt = $this->pdo->prepare($sql);
        if ($grade_id) {
            $stmt->execute([$grade_id]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>