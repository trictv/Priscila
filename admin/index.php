<?php
session_start();

// Include core files
require_once '../src/config.php';
require_once '../src/Database.php';

// Check if the user is logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // User is logged in, show the dashboard or route to other admin pages
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

    // A simple router
    switch ($page) {
        case 'dashboard':
            include 'dashboard.php';
            break;
        case 'products':
            // Later, this will include products.php
            echo "<h1>Product Management</h1>"; // Placeholder
            break;
        case 'logout':
            // Unset all of the session variables
            $_SESSION = array();
            // Destroy the session
            session_destroy();
            // Redirect to login page
            header("Location: index.php");
            exit;
        default:
            include 'dashboard.php'; // Default to dashboard
            break;
    }
} else {
    // User is not logged in, show the login form
    include 'login.php';
}
