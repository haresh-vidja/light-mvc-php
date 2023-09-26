<?php
namespace Core;

/**
 * ============================================================================
 * Class: Config
 * ----------------------------------------------------------------------------
 * Purpose:
 * - Central configuration manager for the application.
 * - Allows loading, accessing, and updating nested configuration values.
 *
 * Features:
 * Load entire config arrays
 * Get values using dot notation (e.g., cache.enable)
 * Set or override values dynamically
 * ============================================================================
 */
class Config
{
    /**
     * @var array $config
     * Internal static array to hold configuration key-value pairs.
     */
    static $config = [];

    /**
     * Load configuration values (usually from an array in config.php)
     *
     * @param array $config  The full configuration array to merge into global config.
     */
    static function load($config)
    {
        // Merge incoming config with existing global config
        self::$config = array_merge(self::$config, $config);
    }

    /**
     * Set or update a configuration value using dot notation.
     *
     * Example:
     * Config::set('database.host', 'localhost');
     *
     * @param string $key    Dot-notated config key (e.g., 'smtp.port')
     * @param mixed  $value  Value to set
     */
    static function set($key, $value)
    {
        // Split the key by dot notation into an array
        $keyArr = array_filter(explode(".", $key));

        // Start from the root of config array
        $currArr = &self::$config;

        // Traverse the nested levels to reach the final key
        do {
            $key = $keyArr[0];

            // If only one key remains, we're at the target level
            if (count($keyArr) === 1) break;

            // Initialize the key as an array if it doesn't exist
            if (!isset($currArr[$key])) {
                $currArr[$key] = [];
            }

            // Traverse deeper
            $currArr = &$currArr[$key];

            // Shift current key
            array_shift($keyArr);
        } while (true);

        // Set the final key's value
        $currArr[$key] = $value;
    }

    /**
     * Retrieve a configuration value using dot notation.
     *
     * Example:
     * $host = Config::get('database.host');
     *
     * @param string $key  Dot-notated config key
     * @return mixed|null  The value if found, otherwise null
     */
    static function get($key)
    {
        // Split dot notation into array keys
        $keyArr = array_filter(explode(".", $key));

        // Start from root config
        $currArr = &self::$config;
        $empty = false;

        do {
            $key = $keyArr[0];

            // Stop loop if we reach the last key
            if (count($keyArr) === 1) break;

            // Traverse down to next level
            $currArr = &$currArr[$key];

            // If the expected key is missing or not an array, exit early
            if (!isset($currArr[$key]) || empty($currArr[$key]) || !is_array($currArr[$key])) {
                $empty = true;
                break;
            }

            // Remove current key from the chain
            array_shift($keyArr);
        } while (true);

        // Return final value or null if not found
        if (!$empty && isset($currArr[$key])) {
            return $currArr[$key];
        } else {
            return null;
        }
    }
}
