<?php
namespace Core;

use PDO;

/**
 * ============================================================================
 * Class: Database
 * ----------------------------------------------------------------------------
 * Purpose:
 * - Provides a shared, reusable PDO connection across the application.
 * - Uses lazy loading: the connection is established only once when needed.
 * - Configuration is loaded from `Config::get("database")`.
 *
 * Usage:
 * - Automatically opens a connection on instantiation.
 * - Use `Database::get()` to retrieve the active PDO instance.
 * ============================================================================
 */
class Database
{
    /**
     * PDO connection options.
     * - Throws exceptions on errors (better for debugging/logging).
     * - Fetches results as associative arrays by default.
     */
    static public $options = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    /**
     * Static property to hold the singleton database connection.
     *
     * @var PDO|null
     */
    static public $connection = null;

    /**
     * Constructor
     * Automatically opens the database connection when the class is instantiated.
     */
    public function __construct()
    {
        $this->openConnection();
    }

    /**
     * Destructor
     * - No specific teardown required; PDO handles cleanup on script termination.
     */
    public function __destruct()
    {
        // Optional: unset(self::$connection); if you want manual cleanup.
    }

    /**
     * Get the current active PDO connection.
     *
     * @return PDO|null
     */
    public function get()
    {
        return self::$connection;
    }

    /**
     * Open a PDO connection to the MySQL database.
     * - Uses configuration from `Config::get("database")`.
     * - Creates only one instance of PDO (singleton behavior).
     */
    public static function openConnection()
    {
        // If connection is already active, skip re-initialization
        if (self::$connection === null) {
            try {
                // Load DB credentials from config
                $database = Config::get("database");

                // Create new PDO instance with host, DB name, user, and password
                self::$connection = new PDO(
                    "mysql:host=" . $database['host'] . ";dbname=" . $database['name'],
                    $database['user'],
                    $database['password'],
                    self::$options
                );
            } catch (\PDOException $e) {
                // Log error message if connection fails
                Logger::debug("There is some problem in connection: " . $e->getMessage());
            }
        }
    }
}
