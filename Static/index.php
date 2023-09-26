<?php
/**
 * ============================================================================
 * ENTRY POINT: index.php
 * ----------------------------------------------------------------------------
 * Purpose:
 * - This is the main entry file for handling all incoming HTTP web requests.
 * - It initializes the framework, loads configurations, and executes the router.
 *
 * Request Lifecycle:
 * 1. Define core directory paths (APP_ROOT, DS).
 * 2. Load application setup via Include.php.
 * 3. Instantiate Core\Http to handle static pages or controller routing.
 *
 * File Location:
 * - Typically located at /public/index.php
 * - Point your web server's document root to this file (e.g., in Apache/Nginx)
 *
 * Key File Dependencies:
 * - /Application/Core/Include.php       → Loads config, classes, and cache
 * - /Application/Core/Http.php          → Routes request to a page or controller
 * - /Application/Pages/ or /Controllers → Defines view or logic handlers
 *
 * Usage:
 * - Automatically executed when a browser accesses the site via HTTP.
 * - No CLI usage. Use run.php for command-line operations.
 * ============================================================================
 */
// -----------------------------------------------------------------------------
// Define directory separator (cross-platform support)
define("DS", DIRECTORY_SEPARATOR);

// -----------------------------------------------------------------------------
// Define application root path (points to /Application directory)
define("APP_ROOT", dirname(__DIR__) . DS . "Application" . DS);

// -----------------------------------------------------------------------------
// Load application bootstrap, configs, and autoloaders
require(APP_ROOT . "Core" . DS . "Include.php");

// -----------------------------------------------------------------------------
// Instantiate and execute HTTP handler to process the request
$http = new Core\Http();
$http->execute();
