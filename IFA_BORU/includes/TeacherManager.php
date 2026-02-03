<?php
/**
 * ============================================
 * TEACHER MANAGEMENT CLASS
 * ============================================
 */

class TeacherManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Generate unique teacher code
     */
    private function generateTeacherCode() {
        $prefix = 'TCH';
        $year = date('Y');
        $maxId = $this->pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn() + 1;
        return $prefix . $year . str_pad($maxId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Add new teacher
     */
    public function addTeacher($data) {
        try {
            $teacher_code = $this->generateTeacherCode();
            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 10]);
            $hire_date = date('Y-m-d');

            $query = "INSERT INTO teachers 
                      (teacher_code, full_name, email, phone, username, password, 
                       date_of_birth, gender, address, hire_date, status)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->pdo->prepare($query);
            
            $result = $stmt->execute([
                $teacher_code,
                $data['full_name'],
                $data['email'],
                $data['phone'] ?? '',
                $data['username'],
                $password_hash,
                $data['date_of_birth'] ?? null,
                $data['gender'] ?? 'Male',
                $data['address'] ?? '',
                $hire_date,
                'Active'
            ]);

            return $result ? $this->pdo->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Update teacher
     */
    public function updateTeacher($teacher_id, $data) {
        try {
            $query = "UPDATE teachers SET 
                      full_name = ?, email = ?, phone = ?, 
                      date_of_birth = ?, gender = ?, address = ?, status = ?
                      WHERE teacher_id = ?";

            $stmt = $this->pdo->prepare($query);
            
            return $stmt->execute([
                $data['full_name'],
                $data['email'],
                $data['phone'] ?? '',
                $data['date_of_birth'] ?? null,
                $data['gender'] ?? 'Male',
                $data['address'] ?? '',
                $data['status'] ?? 'Active',
                $teacher_id
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Get teacher by ID
     */
    public function getTeacherById($teacher_id) {
        try {
            $query = "SELECT * FROM teachers WHERE teacher_id = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$teacher_id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * Get all teachers
     */
    public function getAllTeachers($page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            
            $query = "SELECT * FROM teachers
                      ORDER BY created_at DESC
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
     * Get total teachers count
     */
    public function getTotalTeachers() {
        try {
            return $this->pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Delete teacher
     */
    public function deleteTeacher($teacher_id) {
        try {
            $query = "DELETE FROM teachers WHERE teacher_id = ?";
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute([$teacher_id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Search teachers
     */
    public function searchTeachers($search_term) {
        try {
            $search = "%$search_term%";
            $query = "SELECT * FROM teachers 
                      WHERE teacher_code LIKE ? OR full_name LIKE ? OR email LIKE ? OR username LIKE ?
                      ORDER BY full_name ASC";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$search, $search, $search, $search]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}

?>
