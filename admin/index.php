<?php
$admin_page_title = 'Dashboard';
require_once 'includes/header.php'; // Inclui o cabeçalho e a proteção de login

// Lógica para buscar dados para o dashboard (ex: contagem de produtos)
try {
    $product_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $category_count = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $banner_count = $pdo->query("SELECT COUNT(*) FROM banners")->fetchColumn();
} catch (PDOException $e) {
    $product_count = 'N/A';
    $category_count = 'N/A';
    $banner_count = 'N/A';
    error_log("Erro no dashboard: " . $e->getMessage());
}
?>

<div class="dashboard-container">
    <div class="welcome-message card">
        <h3>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h3>
        <p>Este é o seu painel de controle. Use o menu à esquerda para gerenciar o conteúdo do seu site.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card card">
            <h4>Total de Produtos</h4>
            <p class="stat-number"><?php echo $product_count; ?></p>
            <a href="gerenciar-produtos.php">Ver produtos</a>
        </div>
        <div class="stat-card card">
            <h4>Total de Categorias</h4>
            <p class="stat-number"><?php echo $category_count; ?></p>
            <a href="gerenciar-categorias.php">Ver categorias</a>
        </div>
        <div class="stat-card card">
            <h4>Banners Ativos</h4>
            <p class="stat-number"><?php echo $banner_count; ?></p>
            <a href="gerenciar-banners.php">Ver banners</a>
        </div>
    </div>
</div>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    .stat-card {
        text-align: center;
    }
    .stat-card h4 {
        margin: 0 0 10px;
        font-size: 1rem;
        color: #555;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0 0 15px;
    }
    .stat-card a {
        text-decoration: none;
        color: var(--secondary-color);
        font-weight: bold;
    }
</style>

<?php
require_once 'includes/footer.php';
?>
