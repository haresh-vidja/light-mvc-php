<?php
namespace Core;

/**
 * ============================================================================
 * Class: Logger
 * ----------------------------------------------------------------------------
 * Purpose:
 * - A lightweight static logger utility for capturing messages, warnings, errors,
 *   and execution timing in a consistent format.
 * - Can log to STDOUT and/or a file, with real-time or buffered behavior.
 * - Supports manual or automatic flushing of logs.
 *
 * Features:
 * - Logging levels: info, debug, warning, error
 * - Optional message title (e.g., "DB => Connection failed")
 * - JavaScript-style timing using time() and timeEnd()
 * - Configurable file output (append/overwrite, location, name)
 * ============================================================================
 */
class Logger
{
    /**
     * Stores all log entries in memory.
     * Each entry is an associative array:
     * - timestamp: UNIX time
     * - level: log level (info, debug, warning, error)
     * - name: optional label/title
     * - message: the actual log message
     */
    protected static $log = [];

    /** Whether to print logs to the screen (STDOUT) */
    public static $print_log = true;

    /** Whether to write logs to a file in real-time */
    public static $write_log = false;

    /** Directory where the log file will be saved */
    public static $log_dir = __DIR__;

    /** Name of the log file (without extension) */
    public static $log_file_name = "log";

    /** File extension for log files */
    public static $log_file_extension = "log";

    /** Whether to append to the log file (true) or overwrite (false) */
    public static $log_file_append = true;

    /** Full path to the log file, built at runtime */
    private static $log_file_path = '';

    /** Array of output streams: STDOUT and/or file handle(s) */
    private static $output_streams = [];

    /** Flag to check if the logger has already been initialized */
    private static $logger_ready = false;

    /** Tracks start times for labeled time measurements */
    private static $time_tracking = [];

    // ------------------ LOG LEVEL SHORTCUTS ------------------

    /** Log an informational message */
    public static function info($message, $name = '')
    {
        return self::add($message, $name, 'info');
    }

    /** Log a debug/developer-level message */
    public static function debug($message, $name = '')
    {
        return self::add($message, $name, 'debug');
    }

    /** Log a warning (something might go wrong) */
    public static function warning($message, $name = '')
    {
        return self::add($message, $name, 'warning');
    }

    /** Log an error (something definitely went wrong) */
    public static function error($message, $name = '')
    {
        return self::add($message, $name, 'error');
    }

    // ------------------ TIMING UTILITIES ------------------

    /**
     * Start tracking execution time for a labeled block.
     * 
     * @param string $name  A unique identifier for the timer.
     * @return float|false  The start time or false if already started.
     */
    public static function time(string $name)
    {
        if (!isset(self::$time_tracking[$name])) {
            self::$time_tracking[$name] = microtime(true);
            return self::$time_tracking[$name];
        }
        return false;
    }

    /**
     * End a timing block and log how long it took.
     * 
     * @param string $name  The name used in `time()`.
     * @return float|false  Elapsed time in seconds or false if not found.
     */
    public static function timeEnd(string $name)
    {
        if (isset(self::$time_tracking[$name])) {
            $start = self::$time_tracking[$name];
            $end = microtime(true);
            $elapsed_time = number_format($end - $start, 2);
            unset(self::$time_tracking[$name]);

            self::add("$elapsed_time seconds", "'$name' took", "timing");

            return $elapsed_time;
        }
        return false;
    }

    // ------------------ CORE ADD FUNCTION ------------------

    /**
     * Internal method to add a log entry.
     *
     * @param string $message  The message to log.
     * @param string $name     Optional label (e.g., 'DB', 'Email').
     * @param string $level    Log level: info, debug, warning, error.
     * @return array           The structured log entry.
     */
    private static function add($message, $name = '', $level = 'debug')
    {
        $log_entry = [
            'timestamp' => time(),
            'name'      => $name,
            'message'   => $message,
            'level'     => $level,
        ];

        // Store in in-memory log
        self::$log[] = $log_entry;

        // Initialize logger if not already done
        if (!self::$logger_ready) {
            self::init();
        }

        // Write or print log entry if output streams are available
        if (self::$logger_ready && count(self::$output_streams) > 0) {
            $output_line = self::format_log_entry($log_entry) . PHP_EOL;

            foreach (self::$output_streams as $stream) {
                @fputs($stream, $output_line);
            }
        }

        return $log_entry;
    }

    // ------------------ FORMATTING ------------------

    /**
     * Convert a log entry array into a human-readable log line.
     *
     * @param array $log_entry
     * @return string
     */
    public static function format_log_entry(array $log_entry): string
    {
        if (empty($log_entry)) return '';

        // Convert all values to strings
        $log_entry = array_map(function ($v) {
            return print_r($v, true);
        }, $log_entry);

        $log_line = date('c', $log_entry['timestamp']) . " ";
        $log_line .= "[" . strtoupper($log_entry['level']) . "] : ";

        if (!empty($log_entry['name'])) {
            $log_line .= $log_entry['name'] . " => ";
        }

        $log_line .= $log_entry['message'];

        return $log_line;
    }

    // ------------------ INITIALIZATION ------------------

    /**
     * Prepares output streams for writing/printing logs.
     * Called once automatically.
     */
    public static function init()
    {
        if (!self::$logger_ready) {
            // STDOUT log
            if (self::$print_log === true) {
                @self::$output_streams['stdout'] = STDOUT;
            }

            // Build full log file path
            if (file_exists(self::$log_dir)) {
                self::$log_file_path = implode(DIRECTORY_SEPARATOR, [
                    self::$log_dir,
                    self::$log_file_name
                ]);

                if (!empty(self::$log_file_extension)) {
                    self::$log_file_path .= "." . self::$log_file_extension;
                }
            }

            // File output
            if (self::$write_log === true && file_exists(self::$log_dir)) {
                $mode = self::$log_file_append ? "a" : "w";
                self::$output_streams[self::$log_file_path] = fopen(self::$log_file_path, $mode);
            }

            // Mark logger as ready
            self::$logger_ready = true;
        }
    }

    // ------------------ DUMP UTILITIES ------------------

    /**
     * Write all accumulated log entries to a file.
     *
     * @param string $file_path Optional path. Defaults to the logger's default path.
     */
    public static function dump_to_file($file_path = '')
    {
        if (empty($file_path)) {
            $file_path = self::$log_file_path;
        }

        if (file_exists(dirname($file_path))) {
            $mode = self::$log_file_append ? "a" : "w";
            $output_file = fopen($file_path, $mode);

            foreach (self::$log as $log_entry) {
                $log_line = self::format_log_entry($log_entry);
                fwrite($output_file, $log_line . PHP_EOL);
            }

            fclose($output_file);
        }
    }

    /**
     * Dump all logs into a string (e.g., for web output or email).
     *
     * @return string The entire log in formatted string form.
     */
    public static function dump_to_string()
    {
        $output = '';

        foreach (self::$log as $log_entry) {
            $log_line = self::format_log_entry($log_entry);
            $output .= $log_line . PHP_EOL;
        }

        return $output;
    }
}
