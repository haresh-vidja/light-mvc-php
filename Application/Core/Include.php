<?php

// Use necessary core classes
use Core\Utils;
use Core\Config;

/**
 * ============================================================================
 * File: Include.php
 * ----------------------------------------------------------------------------
 * Purpose:
 * - This file is automatically included at the beginning of the application.
 * - It sets up directory paths, loads configuration, enables error/log handling,
 *   registers the autoloader, and initializes page cache if enabled.
 * ============================================================================
 */

/**
 * ------------------------------------------------------------------
 * Define Core Directory Constants
 * ------------------------------------------------------------------
 */

// Directory for static assets (e.g., CSS, JS, images)
define("APP_STATIC", dirname(APP_ROOT) . DS . "Static" . DS);

// Directory for core framework files (e.g., Http, Config, Logger, etc.)
define("APP_CORE", APP_ROOT . "Core" . DS);

// Temporary storage (e.g., for cache files)
define("TEMP_DIR", APP_ROOT . "Temp" . DS);

// Directory where static PHP pages are stored
define("PAGE_DIR", APP_ROOT . "Pages" . DS);

// Directory for reusable partial templates (header, footer, etc.)
define("PARTIALS", PAGE_DIR . "Partials" . DS);

/**
 * ------------------------------------------------------------------
 * Load Core Autoloader
 * ------------------------------------------------------------------
 * Automatically loads PHP classes based on namespace and file path.
 */
include_once(APP_CORE . "AutoLoad.php");

/**
 * ------------------------------------------------------------------
 * Load Configuration
 * ------------------------------------------------------------------
 * Loads and merges settings from Config/config.php into the global config.
 */
Config::load(require_once(APP_ROOT . "Config" . DS . "Config.php"));

/**
 * ------------------------------------------------------------------
 * Error & Log Management
 * ------------------------------------------------------------------
 * - Sets up custom error and exception handlers.
 * - Initializes logging mechanism if enabled.
 */
Utils::handleErrors();
Utils::handleLogs();

/**
 * ------------------------------------------------------------------
 * ⚡ Page Caching System (GET only)
 * ------------------------------------------------------------------
 * If caching is enabled in config AND the script is NOT running via CLI:
 * - Try to load a previously cached HTML page.
 * - If found, output it and skip rendering logic.
 */
if (Config::get("cache.enable") == true && PHP_SAPI != "cli") {
    if (Core\Http::loadPageFromCache()) {
        exit(); // Serve cached page and stop further processing
    }
}

/**
 * ------------------------------------------------------------------
 * Composer Autoloader (optional)
 * ------------------------------------------------------------------
 * Loads third-party libraries (e.g., PHPMailer, Guzzle) from /vendor.
 */
include_once APP_ROOT . 'vendor' . DS . 'autoload.php';
