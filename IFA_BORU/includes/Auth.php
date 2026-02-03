<?php
/**
 * ============================================
 * Authentication & Session Management
 * ============================================
 */

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Hash password using bcrypt
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    /**
     * Verify password against hash
     */
    public static function verifyPassword($password, $hash) {
        // Handle both hashed and plain text passwords for compatibility
        if (password_verify($password, $hash)) {
            return true;
        }
        // Fallback for plain text passwords (temporary)
        return $password === $hash;
    }

    /**
     * Admin Login
     */
    public function adminLogin($username, $password) {
        try {
            $query = "SELECT * FROM admins WHERE username = ? OR email = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$username, $username]);
            $admin = $stmt->fetch();

            if ($admin && self::verifyPassword($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['user_type'] = 'admin';
                $_SESSION['login_time'] = time();

                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Teacher Login
     */
    public function teacherLogin($username, $password) {
        try {
            $query = "SELECT * FROM teachers WHERE (username = ? OR email = ?) AND status = 'Active'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$username, $username]);
            $teacher = $stmt->fetch();

            if ($teacher && self::verifyPassword($password, $teacher['password'])) {
                $_SESSION['teacher_id'] = $teacher['teacher_id'];
                $_SESSION['teacher_code'] = $teacher['teacher_code'];
                $_SESSION['teacher_name'] = $teacher['full_name'];
                $_SESSION['teacher_username'] = $teacher['username'];
                $_SESSION['teacher_email'] = $teacher['email'];
                $_SESSION['user_type'] = 'teacher';
                $_SESSION['login_time'] = time();

                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Student Login
     */
    public function studentLogin($student_code, $dob) {
        try {
            $query = "SELECT * FROM students WHERE student_code = ? AND date_of_birth = ? AND status = 'Active'";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$student_code, $dob]);
            $student = $stmt->fetch();

            if ($student) {
                $_SESSION['student_id'] = $student['student_id'];
                $_SESSION['student_code'] = $student['student_code'];
                $_SESSION['student_name'] = $student['full_name'];
                $_SESSION['grade_id'] = $student['grade_id'];
                $_SESSION['section_id'] = $student['section_id'];
                $_SESSION['user_type'] = 'student';
                $_SESSION['login_time'] = time();

                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_type']);
    }

    /**
     * Check if admin is logged in
     */
    public static function isAdminLoggedIn() {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
    }

    /**
     * Check if teacher is logged in
     */
    public static function isTeacherLoggedIn() {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'teacher';
    }

    /**
     * Check if student is logged in
     */
    public static function isStudentLoggedIn() {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student';
    }

    /**
     * Check session timeout
     */
    public static function checkSessionTimeout() {
        if (self::isLoggedIn() && isset($_SESSION['login_time'])) {
            $elapsed = time() - $_SESSION['login_time'];
            if ($elapsed > SESSION_TIMEOUT * 60) {
                self::logout();
                return false;
            }
            $_SESSION['login_time'] = time(); // Reset timer
        }
        return true;
    }

    /**
     * Logout user
     */
    public static function logout() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Destroy all session data
        session_unset();
        session_destroy();
        
        // Redirect to login page
        header('Location: ../index.php');
        exit;
    }
}

// Check session timeout
Auth::checkSessionTimeout();

?>