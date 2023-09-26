<?php
/**
 * ===========================================================
 * Class: ApplicationAutoloader
 * -----------------------------------------------------------
 * Purpose:
 * - Dynamically loads PHP classes based on their namespace and
 *   file path when they're used, avoiding manual includes.
 * - Registers an autoloader using SPL (Standard PHP Library).
 * 
 * How it works:
 * - When a class is referenced but not yet loaded, PHP triggers
 *   this loader function.
 * - The loader converts the namespace to a valid file path
 *   based on the project's directory structure.
 * ===========================================================
 */
class ApplicationAutoloader {

    /**
     * Loader function to automatically include class files.
     *
     * @param string $className The fully-qualified class name with namespace.
     * @return bool             True if class is successfully loaded, false otherwise.
     */
    public static function loader($className) {
        // [Optional Optimization Placeholder]
        // If the class already exists, return early (currently disabled by 'false &&')
        if (false && class_exists($className)) {
            return true;
        }

        /**
         * Convert namespace to file path:
         * Example: Core\Logger → /Application/Core/Logger.php
         */
        $filename = APP_ROOT . str_replace("\\", DS, $className) . ".php";

        // If the file exists, include it
        if (file_exists($filename)) {
            include($filename);

            // Verify that the class was actually declared in the file
            if (class_exists($className)) {
                return true;
            }
        }

        // If file not found or class not defined, return false
        return false;
    }
}

// Register the loader with PHP's SPL autoload stack
spl_autoload_register('ApplicationAutoloader::loader');
