<?php
/**
 * ============================================
 * STUDENT MANAGEMENT CLASS
 * ============================================
 */

class StudentManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Generate unique student code
     */
    private function generateStudentCode() {
        $prefix = 'STU';
        $year = date('Y');
        $maxId = $this->pdo->query("SELECT COUNT(*) FROM students")->fetchColumn() + 1;
        return $prefix . $year . str_pad($maxId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Add new student
     */
    public function addStudent($data) {
        try {
            $student_code = $this->generateStudentCode();
            $enrolled_date = date('Y-m-d');

            $query = "INSERT INTO students 
                      (student_code, full_name, date_of_birth, gender, grade_id, section_id, 
                       parent_name, parent_phone, address, email, phone, status, enrolled_date)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->pdo->prepare($query);
            
            $result = $stmt->execute([
                $student_code,
                $data['full_name'],
                $data['date_of_birth'],
                $data['gender'] ?? 'Male',
                $data['grade_id'],
                $data['section_id'],
                $data['parent_name'] ?? '',
                $data['parent_phone'] ?? '',
                $data['address'] ?? '',
                $data['email'] ?? '',
                $data['phone'] ?? '',
                'Active',
                $enrolled_date
            ]);

            return $result ? $this->pdo->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Update student
     */
    public function updateStudent($student_id, $data) {
        try {
            $query = "UPDATE students SET 
                      full_name = ?, date_of_birth = ?, gender = ?, grade_id = ?, 
                      section_id = ?, parent_name = ?, parent_phone = ?, 
                      address = ?, email = ?, phone = ?, status = ?
                      WHERE student_id = ?";

            $stmt = $this->pdo->prepare($query);
            
            return $stmt->execute([
                $data['full_name'],
                $data['date_of_birth'],
                $data['gender'] ?? 'Male',
                $data['grade_id'],
                $data['section_id'],
                $data['parent_name'] ?? '',
                $data['parent_phone'] ?? '',
                $data['address'] ?? '',
                $data['email'] ?? '',
                $data['phone'] ?? '',
                $data['status'] ?? 'Active',
                $student_id
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Get student by ID
     */
    public function getStudentById($student_id) {
        try {
            $query = "SELECT s.*, g.grade_level, sec.section_name 
                      FROM students s
                      JOIN grades g ON s.grade_id = g.grade_id
                      JOIN sections sec ON s.section_id = sec.section_id
                      WHERE s.student_id = ?";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$student_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * Get all students
     */
    public function getAllStudents($page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            
            $query = "SELECT s.*, g.grade_level, sec.section_name 
                      FROM students s
                      JOIN grades g ON s.grade_id = g.grade_id
                      JOIN sections sec ON s.section_id = sec.section_id
                      ORDER BY s.created_at DESC
                      LIMIT ? OFFSET ?";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Get total students count
     */
    public function getTotalStudents() {
        try {
            return $this->pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Delete student
     */
    public function deleteStudent($student_id) {
        try {
            $query = "DELETE FROM students WHERE student_id = ?";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([$student_id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Search students
     */
    public function searchStudents($search_term) {
        try {
            $search = "%$search_term%";
            $query = "SELECT s.*, g.grade_level, sec.section_name 
                      FROM students s
                      JOIN grades g ON s.grade_id = g.grade_id
                      JOIN sections sec ON s.section_id = sec.section_id
                      WHERE s.student_code LIKE ? OR s.full_name LIKE ? OR s.email LIKE ?
                      ORDER BY s.full_name ASC";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$search, $search, $search]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Get students by grade and section
     */
    public function getStudentsByGradeAndSection($grade_id, $section_id) {
        try {
            $query = "SELECT s.* FROM students s
                      WHERE s.grade_id = ? AND s.section_id = ? AND s.status = 'Active'
                      ORDER BY s.full_name ASC";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$grade_id, $section_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}

?>
