<?php
/**
 * Database Configuration
 * 
 * This file contains the database connection settings.
 * Update these values according to your MySQL server configuration.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'cental_db');
define('DB_USER', 'root');
define('DB_PASS', 'admin123');

/**
 * Get PDO database connection
 * 
 * @return PDO Database connection instance
 * @throws PDOException If connection fails
 */
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    return $pdo;
}
?>

