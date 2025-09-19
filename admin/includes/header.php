<?php
session_start();
require_once '../includes/db_connect.php';

// Proteção: se o usuário não estiver logado, redireciona para a página de login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Define o título padrão que pode ser sobrescrito
$admin_page_title = isset($admin_page_title) ? $admin_page_title : 'Dashboard';

// Pega o nome do arquivo atual para destacar o link ativo no menu
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($admin_page_title); ?> - Admin BEEFIT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Estilos básicos para o painel admin */
        :root { --primary-color: #D4AF37; --secondary-color: #2c3e50; --light-gray: #f8f9fa; }
        body { font-family: 'Lato', sans-serif; margin: 0; background-color: var(--light-gray); display: flex; }
        .sidebar { width: 250px; background-color: var(--secondary-color); color: #fff; height: 100vh; padding: 20px; position: fixed; }
        .sidebar h2 { font-family: 'Montserrat', sans-serif; text-align: center; color: var(--primary-color); margin: 0 0 30px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar a { color: #ecf0f1; text-decoration: none; padding: 12px 15px; display: block; border-radius: 4px; transition: background-color 0.3s; }
        .sidebar a.active, .sidebar a:hover { background-color: #34495e; }
        .sidebar .logout-link { position: absolute; bottom: 20px; width: calc(100% - 40px); text-align: center; background-color: #c0392b; }
        .sidebar .logout-link:hover { background-color: #e74c3c; }
        .main-content { margin-left: 250px; flex: 1; padding: 30px; }
        .main-content h1 { font-family: 'Montserrat', sans-serif; color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 15px; border-radius: 4px; text-decoration: none; font-weight: bold; border: none; cursor: pointer; }
        .btn-primary { background-color: var(--primary-color); color: var(--secondary-color); }
        .btn-danger { background-color: #e74c3c; color: #fff; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>BEEFIT Admin</h2>
        <nav>
            <ul>
                <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="gerenciar-produtos.php" class="<?php echo ($current_page == 'gerenciar-produtos.php') ? 'active' : ''; ?>">Produtos</a></li>
                <li><a href="gerenciar-categorias.php" class="<?php echo ($current_page == 'gerenciar-categorias.php') ? 'active' : ''; ?>">Categorias</a></li>
                <li><a href="gerenciar-banners.php" class="<?php echo ($current_page == 'gerenciar-banners.php') ? 'active' : ''; ?>">Banners Home</a></li>
                <li><a href="configuracoes.php" class="<?php echo ($current_page == 'configuracoes.php') ? 'active' : ''; ?>">Configurações</a></li>
            </ul>
        </nav>
        <a href="logout.php" class="logout-link">Sair</a>
    </aside>

    <main class="main-content">
        <h1><?php echo htmlspecialchars($admin_page_title); ?></h1>
        <!-- Conteúdo da página específica começa aqui -->
