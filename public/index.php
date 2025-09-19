<?php
// Main router for the public-facing site

// Include configuration and database
require_once '../src/config.php';
require_once '../src/Database.php';

// Get the requested page, default to 'home'
$page = $_GET['page'] ?? 'home';

// A simple router to include the correct page content
switch ($page) {
    case 'home':
        include 'pages/home.php';
        break;
    case 'products':
        include 'pages/products.php';
        break;
    case 'product':
        // This will handle individual product details
        include 'pages/product_details.php';
        break;
    case 'about':
        include 'pages/about.php';
        break;
    default:
        // Show a 404 page for any other value
        http_response_code(404);
        include 'pages/404.php';
        break;
}
