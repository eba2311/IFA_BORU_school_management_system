<?php
/**
 * ============================================
 * Input Validation & Security Functions
 * ============================================
 */

class Validator {
    /**
     * Sanitize string input
     */
    public static function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate email
     */
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate phone number (Ethiopian format)
     */
    public static function isValidPhone($phone) {
        return preg_match('/^(\+251|0)?[19]\d{8}$/', $phone);
    }

    /**
     * Validate date format (YYYY-MM-DD)
     */
    public static function isValidDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Validate password strength
     */
    public static function isStrongPassword($password) {
        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            return false;
        }
        // At least one uppercase, one lowercase, one digit, one special char
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
    }

    /**
     * Validate username
     */
    public static function isValidUsername($username) {
        return preg_match('/^[a-zA-Z0-9_.-]{3,20}$/', $username);
    }
}

?>
