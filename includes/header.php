<?php
// Inicia a sessão em todas as páginas para futuras funcionalidades
session_start();

// Inclui a conexão com o banco de dados e busca as configurações
require_once 'db_connect.php';

// Define um título padrão, que pode ser sobrescrito pela página que o inclui
$page_title = isset($page_title) ? $page_title : 'BEEFIT - Moda e Estilo';

// Define caminhos para assets para facilitar a manutenção
$base_url = "/beefit/"; // Ajuste se o site não estiver na raiz do subdomínio
$logo_path = isset($settings['site_logo']) ? $base_url . $settings['site_logo'] : $base_url . 'assets/images/default_logo.png';
$title_color = isset($settings['title_color']) ? $settings['title_color'] : '#1a1a1a';
$button_color = isset($settings['button_color']) ? $settings['button_color'] : '#D4AF37';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <!-- SEO e Metatags -->
    <meta name="description" content="BEEFIT - Encontre as melhores tendências em moda e calçados.">
    <meta name="keywords" content="moda, calçados, roupas, beefit, estilo">

    <!-- Google Fonts (Exemplo: Montserrat e Lato) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">

    <!-- CSS Principal -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">

    <!-- Estilos Dinâmicos (cores do admin) -->
    <style>
        :root {
            --title-color: <?php echo htmlspecialchars($title_color); ?>;
            --button-color: <?php echo htmlspecialchars($button_color); ?>;
        }
    </style>
</head>
<body>

<header class="main-header">
    <div class="container">
        <a href="<?php echo $base_url; ?>index.php" class="logo">
            <img src="<?php echo htmlspecialchars($logo_path); ?>" alt="BEEFIT Logo">
        </a>
        <nav class="main-nav">
            <ul>
                <li><a href="<?php echo $base_url; ?>index.php">Home</a></li>
                <li><a href="<?php echo $base_url; ?>produtos.php">Produtos</a></li>
                <li><a href="<?php echo $base_url; ?>produtos.php?categoria=roupas">Roupas</a></li>
                <li><a href="<?php echo $base_url; ?>produtos.php?categoria=calcados">Calçados</a></li>
                <li><a href="<?php echo $base_url; ?>sobre.php">Sobre Nós</a></li>
            </ul>
        </nav>
        <div class="header-icons">
            <!-- Futuros ícones, como busca ou login de cliente -->
        </div>
    </div>
</header>

<main>
    <!-- O conteúdo da página específica será inserido aqui -->
