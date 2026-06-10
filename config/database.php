<?php
/**
 * Database Configuration and Connection Class
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rehnuma_erp');
define('DB_CHARSET', 'utf8mb4');

class Database {
    private static $connection = null;
    
    public static function getConnection() {
        if (self::$connection === null) {
            self::$connection = new mysqli(
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME
            );
            
            if (self::$connection->connect_error) {
                die('Database Connection Error: ' . self::$connection->connect_error);
            }
            
            self::$connection->set_charset(DB_CHARSET);
        }
        
        return self::$connection;
    }
    
    public static function closeConnection() {
        if (self::$connection !== null) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}
?>