<?php
/**
 * Database Configuration
 */

class Database
{
    private static $instance;
    private $pdo;

    private function __construct()
    {
        try {
            // For production, use environment variables
            $host = getenv('DB_HOST') ?: 'localhost';
            $dbname = getenv('DB_NAME') ?: 'assisted_living_menu';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';

            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            // Fall back to JSON-based storage for development
            $this->initializeJSONStorage();
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    private function initializeJSONStorage()
    {
        // Development fallback - use JSON files
        if (!is_dir(ROOT_PATH . '/data')) {
            mkdir(ROOT_PATH . '/data', 0755, true);
        }
    }
}

// Initialize database
$db = Database::getInstance();
?>