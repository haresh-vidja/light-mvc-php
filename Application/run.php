<?php
/**
 * ============================================================================
 * 🔧 ENTRY SCRIPT: run.php
 * ----------------------------------------------------------------------------
 * Purpose:
 * - This script is used to execute CLI-based commands such as schedulers,
 *   cache operations, or other background jobs.
 *
 * Usage:
 * Run the following command in your terminal from the project root:
 *
 *   php run.php scheduler mail-queue:send
 *   php run.php cache:clear
 *
 * Safety:
 * - This file is protected from browser access and is intended for CLI only.
 * - You can define and handle custom commands in Core/Command.php
 *
 * Required File:
 * - Core/Include.php: Loads autoloaders, config, utilities, and caching logic.
 * ============================================================================
 */

use Core\Command;

// -----------------------------------------------------------------------------
// Prevent execution from a browser or non-CLI environment
// -----------------------------------------------------------------------------
if (PHP_SAPI != "cli") {
    echo "Executed from the web browser! \n";
    exit();
}

// -----------------------------------------------------------------------------
// Define directory constants used across the application
// -----------------------------------------------------------------------------
define("DS", DIRECTORY_SEPARATOR);           // Directory separator
define("IS_COMMAND", true);                  // Set flag to indicate CLI execution
define("APP_ROOT", __DIR__ . DS);            // Root path of the application

// -----------------------------------------------------------------------------
// Include application bootstrap and core setup
// -----------------------------------------------------------------------------
require(APP_ROOT . "Core" . DS . "Include.php");

// -----------------------------------------------------------------------------
// Execute the command based on CLI arguments
// -----------------------------------------------------------------------------
Command::run($argc, $argv);
