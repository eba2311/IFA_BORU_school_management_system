<?php
/**
 * ============================================
 * Database Configuration
 * ============================================
 */

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Change this if your MySQL has a password
define('DB_NAME', 'ifa_boru_sms');
define('DB_PORT', 3306);

// Base URL for the application
define('BASE_URL', 'http://localhost/ifa-boru-sms/');

// Application Settings
define('APP_NAME', 'IFA BORU AMURU SMS');
define('APP_VERSION', '1.0.0');

// Session timeout (in minutes)
define('SESSION_TIMEOUT', 30);

// File upload settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('STUDENT_PHOTO_DIR', UPLOAD_DIR . 'students/');

// Security settings
define('PASSWORD_MIN_LENGTH', 8);
define('HASH_ALGORITHM', 'bcrypt');

// Pagination
define('ITEMS_PER_PAGE', 20);

?>
