<?php

// Import the HTTP handler class
use Core\Http;

// Define directory separator for cross-platform compatibility
define("DS", DIRECTORY_SEPARATOR);

// Define the application root path (used across the app)
define("APP_ROOT", __DIR__ . DS . "Application" . DS);

// Load essential classes and configuration
require(APP_ROOT . "Core" . DS . "Include.php");

// Initialize HTTP handler and process the incoming request
$http = new Http();
$http->execute();
