<?php
/**
 * ============================================
 * Database Connection Class (PDO)
 * ============================================
 */

require_once 'config.php';

class Database {
    private $conn;
    private $error;

    /**
     * Connect to database using PDO
     */
    public function connect() {
        $this->conn = null;

        try {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
            
            $options = [
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];

            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log($this->error);
            die('Database Connection Error: ' . $e->getMessage());
        }

        return $this->conn;
    }

    /**
     * Get database connection
     */
    public function getConnection() {
        return $this->conn ?? $this->connect();
    }

    /**
     * Get last error
     */
    public function getError() {
        return $this->error;
    }
}

?>
