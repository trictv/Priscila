<?php
// At this point, we would fetch global site settings from the database.
// For now, we'll use placeholders or default values from config.
// $db = Database::getInstance()->getConnection();
// $stmt = $db->query("SELECT * FROM settings");
// $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Placeholder values
$site_title = 'BEEFIT - Fashion & Footwear';
$logo_path = 'assets/images/logo.png'; // Placeholder path
$title_color = '#000000'; // Default
$button_color = '#FFA500'; // Default

$current_page = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($site_title); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- TODO: Add Google Fonts link here, e.g., Montserrat and Lato -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">

    <style>
        /* This block allows overriding CSS variables from the database settings */
        :root {
            --title-color: <?php echo htmlspecialchars($title_color ?? '#000000'); ?>;
            --primary-color: <?php echo htmlspecialchars($button_color ?? '#FFA500'); ?>;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <a href="index.php?page=home">
                    <img src="<?php echo htmlspecialchars($logo_path); ?>" alt="BEEFIT Logo">
                </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php?page=home" class="<?php echo ($current_page == 'home') ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="index.php?page=products&cat=roupas" class="<?php echo ($_GET['cat'] ?? '') == 'roupas' ? 'active' : ''; ?>">Roupas</a></li>
                    <li><a href="index.php?page=products&cat=calcados" class="<?php echo ($_GET['cat'] ?? '') == 'calcados' ? 'active' : ''; ?>">Calçados</a></li>
                    <li><a href="index.php?page=products&filter=promocoes" class="<?php echo ($_GET['filter'] ?? '') == 'promocoes' ? 'active' : ''; ?>">Promoções</a></li>
                    <li><a href="index.php?page=about" class="<?php echo ($current_page == 'about') ? 'active' : ''; ?>">Sobre Nós</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <!-- Page content starts here -->
