<?php
namespace Core;

/**
 * ==================================================================
 * Class: Application
 * ------------------------------------------------------------------
 * Purpose:
 * - This class provides utility methods related to application setup.
 * - Currently, it supports dynamic loading of helper classes from
 *   the `/Helpers/` directory using naming conventions.
 * ==================================================================
 */
class Application
{
    /**
     * Load a helper class by name.
     *
     * @param string $helper  The name of the helper (e.g., 'form' for FormHelper).
     * @return bool           Returns true if helper class was loaded successfully.
     */
    public static function loadHelper($helper)
    {
        // Convert helper name to PascalCase and append 'Helper' suffix
        $helperName = Utils::camelize($helper) . "Helper";

        // Build the full file path to the helper file
        $helperFilePath = APP_ROOT . "Helpers" . DS . $helperName . ".php";

        // Check if the helper file exists
        if (file_exists($helperFilePath)) {
            require_once $helperFilePath;

            // Check if the class is defined inside the included file
            if (class_exists($helperName)) {
                return true;
            } else {
                Logger::error("$helperName class not found inside $helperFilePath.");
                return false;
            }
        } else {
            Logger::error("Helper file not found: $helperFilePath.");
            return false;
        }
    }
}
