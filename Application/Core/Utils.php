<?php
namespace Core;

/**
 * ============================================================================
 * Class: Utils
 * ----------------------------------------------------------------------------
 * Purpose:
 * - Provides static utility methods used across the application.
 * - Covers URL handling, error/log management, partial rendering, redirection,
 *   cache clearing, and more.
 * ============================================================================
 */
class Utils
{
    /** 
     * Stores the requested relative URL path (e.g., 'about-us', 'index').
     * Used to reconstruct full URL in getRequestUrl().
     */
    static $url = "";

    /**
     * Returns the full request URL based on `siteUrl` and current path.
     * Example: http://example.com/about-us
     */
    static function getRequestUrl()
    {
        return Config::get("siteUrl") . (self::$url === "index" ? "" : self::$url);
    }

    /**
     * Detects the current domain + base path using server variables.
     * Useful for dynamically determining the root URL.
     */
    static function getSiteUrl()
    {
        $siteUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $siteUrl .= "://" . $_SERVER['HTTP_HOST'];
        $siteUrl .= str_replace("/Static/" . basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
        return $siteUrl;
    }

    /**
     * Loads a partial view (like header/footer/menu) with optional variables.
     *
     * @param string $path        Relative path to the partial (e.g., 'layout/header')
     * @param array  $variables   Associative array of variables to extract in view
     */
    static function partial($path, $variables = array())
    {
        if (!empty($variables)) extract($variables);

        $filepath = PARTIALS . $path . ".php";

        if (file_exists($filepath)) {
            include($filepath);
        }
    }

    /**
     * Configure PHP error handling based on debug mode.
     * - Enables display/logging if debug is true
     * - Hides errors in production (debug = false)
     */
    static function handleErrors()
    {
        // Ensure STDOUT/STDIN/STDERR are defined for CLI-safe usage
        if (!defined('STDIN'))  define('STDIN',  fopen('php://stdin',  'rb'));
        if (!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'wb'));
        if (!defined('STDERR')) define('STDERR', fopen('php://stderr', 'wb'));

        if (Config::get("debug")) {
            Logger::$print_log = true;
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            Logger::$print_log = false;
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(E_ALL); // Errors still logged silently if enabled
        }
    }

    /**
     * Set up file-based logging if enabled in config.
     * - Writes logs to /Temp/Logs/error-YYYY-MM-DD.log
     */
    static function handleLogs()
    {
        if (Config::get("log")) {
            Logger::$log_dir = APP_ROOT . "Temp" . DS . "Logs" . DS;
            Logger::$log_file_name = "error-" . date("Y-m-d");
            Logger::$write_log = true;

            $log_file = TEMP_DIR . "Logs" . DS . "error-" . date("Y-m-d") . ".log";
            ini_set('error_log', $log_file);
        } else {
            Logger::$write_log = false;
        }
    }

    /**
     * Redirects to a given URL and stops script execution.
     *
     * @param string $location  Target URL or path
     */
    static function redirect($location)
    {
        if (ob_get_length() > 0) {
            ob_clean();
        }

        header("Location: " . $location);
        exit();
    }

    /**
     * Deletes all cached page files from the cache directory.
     *
     * @return int  Number of files deleted
     */
    static function clearCache()
    {
        $dir = new \DirectoryIterator(TEMP_DIR . "Cache");
        $count = 0;

        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                unlink($fileinfo->getPathname());
                $count++;
            }
        }

        return $count;
    }

    /**
     * Sends a standardized JSON response and stops execution.
     *
     * @param string $message     Message to show
     * @param int    $errorcode   HTTP-like error/status code
     * @param string $type        Response type ('error', 'info', etc.)
     */
    static function response($message, $errorcode, $type)
    {
        $response = [
            'code' => $errorcode,
            'message' => $message,
            'type' => $type
        ];

        echo json_encode($response);
        exit;
    }

    /**
     * Print a string and exit. Used for debugging or immediate output.
     *
     * @param string $str
     */
    static function print($str)
    {
        echo $str;
        die();
    }

    /**
     * Converts a string to PascalCase format.
     * e.g., 'mail-queue' → 'MailQueue'
     *
     * @param string $string
     * @return string
     */
    static function camelize(string $string): string
    {
        return ucfirst(str_replace(' ', '', ucwords(preg_replace('/[^a-zA-Z0-9\x7f-\xff]+/', ' ', $string))));
    }

    /**
     * Returns the full URL of an image resource with a version string appended.
     *
     * @param string $imagePath Relative path of the image.
     * @return string Full image URL with version query parameter.
     */
    static function getImageURL($imagePath) {
        // Concatenate base image path with relative image path and version string
        return Config::get("resources.image") . $imagePath . self::getVersionString();
    }

    /**
     * Returns the full URL of a JavaScript resource with a version string appended.
     *
     * @param string $jsPath Relative path of the JavaScript file.
     * @return string Full JavaScript URL with version query parameter.
     */
    static function getJsURL($jsPath) {
        // Concatenate base JS path with relative path and version string
        return Config::get("resources.js") . $jsPath . self::getVersionString();
    }

    /**
     * Returns the full URL of a CSS resource with a version string appended.
     *
     * @param string $cssPath Relative path of the CSS file.
     * @return string Full CSS URL with version query parameter.
     */
    static function getCssURL($cssPath) {
        // Concatenate base CSS path with relative path and version string
        return Config::get("resources.css") . $cssPath . self::getVersionString();
    }

    /**
     * Returns the full URL of a favicon resource with a version string appended.
     *
     * @param string $cssPath Relative path of the favicon.
     * @return string Full favicon URL with version query parameter.
     */
    static function getFaviconURL($cssPath) {
        // Concatenate base favicon path with relative path and version string
        return Config::get("resources.favicon") . $cssPath . self::getVersionString();
    }

    /**
     * Generates a version query string for cache-busting (e.g., ?ver=abc123).
     * This helps force browsers to reload updated files.
     * 
     * The version key is expected to be defined in environment or config settings.
     * If no version key is set, an empty string is returned (no query parameter added).
     *
     * @return string Version query string, e.g., "?ver=abc123"
     */
    static function getVersionString() {
        $versionString = '';

        // Fetch the version key from configuration (e.g., cache key)
        if (Config::get('cache.key', '') != '') {
            $versionString = '?ver=' . Config::get('cache.key');
        }

        return $versionString;
    }

}

/**
 * =================================================================
 * Global Helper Functions
 * =================================================================
 * These are globally available debugging tools.
 */

/**
 * Pretty print any variable (array, object, etc.) using <pre> tags.
 *
 * @param mixed $data
 */
function pre($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * Pretty print and die – like `dd()` in Laravel.
 *
 * @param mixed $data
 */
function prd($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}
