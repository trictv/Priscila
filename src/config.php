<?php

/**
 * Configuration for the BEEFIT website.
 *
 * This file contains the database connection settings and other
 * important constants.
 */

// --- Database Configuration ---
define('DB_HOST', '127.0.0.1'); // Or your database host
define('DB_NAME', 'beefit_db');
define('DB_USER', 'root');      // Your database username
define('DB_PASS', 'root');      // Your database password
define('DB_CHARSET', 'utf8mb4');

// --- Site Configuration ---
define('SITE_URL', 'http://localhost/beefit'); // Change this to your actual URL
define('ADMIN_URL', SITE_URL . '/admin');

// --- WhatsApp Configuration ---
// The default number can be overridden by the value in the 'settings' table
define('DEFAULT_WHATSAPP_NUMBER', '5517999999999');

// --- Error Reporting ---
// Set to 'development' or 'production'
define('ENVIRONMENT', 'development');

if (ENVIRONMENT === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}
