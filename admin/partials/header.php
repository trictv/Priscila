<?php
// Ensure the user is logged in and the session is active.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$admin_username = $_SESSION['admin_username'] ?? 'Admin';
$current_page = $_GET['page'] ?? 'dashboard';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - BEEFIT</title>
    <style>
        /* Basic styles for the admin panel */
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; margin: 0; background-color: #f8f9fa; }
        .admin-wrapper { display: flex; }
        .sidebar { width: 250px; background-color: #343a40; color: #fff; height: 100vh; padding: 20px; box-sizing: border-box; position: fixed; }
        .sidebar h2 { text-align: center; color: #FFA500; margin: 0 0 30px 0; }
        .sidebar ul { list-style: none; padding: 0; margin: 0; }
        .sidebar ul li a { color: #adb5bd; text-decoration: none; padding: 10px 15px; display: block; border-radius: 4px; transition: background-color 0.2s, color 0.2s; }
        .sidebar ul li a:hover, .sidebar ul li a.active { background-color: #495057; color: #fff; }
        .sidebar .logout-link { position: absolute; bottom: 20px; width: calc(100% - 40px); }
        .sidebar .logout-link a { background-color: #d9534f; color: white; text-align: center; }
        .sidebar .logout-link a:hover { background-color: #c9302c; }
        .main-content { margin-left: 250px; flex: 1; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .header h1 { margin: 0; font-size: 28px; }
        .header .welcome-msg { font-size: 16px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .action-links a { margin-right: 10px; text-decoration: none; }
        .action-links .edit { color: #5bc0de; }
        .action-links .delete { color: #d9534f; }
        .btn { padding: 10px 15px; border-radius: 4px; text-decoration: none; font-weight: bold; }
        .btn-primary { background-color: #FFA500; color: white; }
        .btn-primary:hover { background-color: #e69500; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <nav class="sidebar">
            <h2>BEEFIT Admin</h2>
            <ul>
                <li><a href="index.php?page=dashboard" class="<?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="index.php?page=products" class="<?php echo ($current_page == 'products') ? 'active' : ''; ?>">Products</a></li>
                <li><a href="index.php?page=categories" class="<?php echo ($current_page == 'categories') ? 'active' : ''; ?>">Categories</a></li>
                <li><a href="index.php?page=settings" class="<?php echo ($current_page == 'settings') ? 'active' : ''; ?>">Site Settings</a></li>
                <li><a href="index.php?page=home-banner" class="<?php echo ($current_page == 'home-banner') ? 'active' : ''; ?>">Home Banner</a></li>
            </ul>
            <div class="logout-link">
                <a href="index.php?page=logout">Logout</a>
            </div>
        </nav>
        <main class="main-content">
            <header class="header">
                <!-- Title will be set by each page -->
                <h1><?php echo ucfirst(str_replace('-', ' ', $current_page)); ?></h1>
                <span class="welcome-msg">Welcome, <?php echo htmlspecialchars($admin_username); ?>!</span>
            </header>

            <!-- Main content of the page will start here -->
