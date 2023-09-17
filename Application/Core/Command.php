<?php
namespace Core;

use Core\Logger;

/**
 * ============================================================================
 * Class: Command
 * ----------------------------------------------------------------------------
 * Purpose:
 * - Handles command-line interface (CLI) commands for the application.
 * - Supports:
 *   1. Running scheduled tasks (`scheduler`)
 *   2. Clearing cached files (`cache:clear`)
 *
 * Usage:
 *   php run.php scheduler mail-queue:send
 *   php run.php cache:clear
 * ============================================================================
 */
class Command
{
    /** @var string $commandType The main command (e.g., 'scheduler', 'cache:clear') */
    public static $commandType;

    /** @var array $commandArgument The arguments passed to the command (e.g., scheduler name and method) */
    public static $commandArgument;

    /**
     * Entry point for executing CLI commands.
     *
     * @param int|null   $argc  Argument count (from PHP CLI)
     * @param array|null $argv  Argument values (from PHP CLI)
     */
    public static function run($argc = null, $argv = null)
    {
        // If no arguments provided, stop execution
        if ($argc == 0) {
            echo "Invalid arguments";
            die();
        }

        // Remove script name from $argv
        array_shift($argv);

        // First argument is the command type (e.g., 'scheduler', 'cache:clear')
        self::$commandType = array_shift($argv);

        // Remaining arguments
        self::$commandArgument = $argv;

        /**
         * Supported command types
         */
        if (self::$commandType == "scheduler") {
            self::executeScheduler();
        }
        else if (self::$commandType == "cache:clear") {

            // Clear cache and log result
            $count = Utils::clearCache();

            if ($count > 0) {
                Logger::debug("There are " . $count . " files deleted from cache.");
            } else {
                Logger::debug("No files found in cache.");
            }

        } else {
            echo "Invalid command arguments";
        }
    }

    /**
     * Execute a scheduler task from the /Schedulers directory.
     * Example: php run.php scheduler mail-queue:send
     */
    public static function executeScheduler()
    {
        // Ensure the scheduler name is provided
        if (empty(self::$commandArgument)) {
            echo "Scheduler name not defined";
            exit;
        }

        /**
         * Parse argument:
         * e.g., mail-queue:send → scheduler = mail-queue, action = send
         */
        $tmpArr = explode(":", self::$commandArgument[0]);

        $scheduler = $tmpArr[0];
        $action = isset($tmpArr[1]) && !empty($tmpArr[1]) ? $tmpArr[1] : "execute";

        // Convert scheduler name to PascalCase + 'Scheduler'
        $schedulerName = Utils::camelize($scheduler) . "Scheduler";

        // Build full path to scheduler file
        $schedulerFilePath = APP_ROOT . "Schedulers" . DS . $schedulerName . ".php";

        // If the file exists, include it and execute the method
        if (file_exists($schedulerFilePath)) {

            // Include base scheduler class if needed
            require_once APP_CORE . "Scheduler.php";

            // Include target scheduler file
            require_once $schedulerFilePath;

            // Check if the class exists
            if (class_exists($schedulerName)) {

                // Instantiate scheduler
                $schedulerObj = new $schedulerName();

                // Check and invoke the desired method (e.g., send, execute, cleanup)
                if (method_exists($schedulerObj, $action)) {
                    $schedulerObj->$action();
                } else {
                    Logger::error("Method " . $action . " not found in Scheduler class " . $schedulerName);
                }

            } else {
                Logger::error("Scheduler class " . $schedulerName . " not found in " . $schedulerFilePath);
            }

        } else {
            Logger::error("Scheduler file not found: " . $schedulerFilePath);
        }
    }
}
