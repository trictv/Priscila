<?php
$page_title = 'BEEFIT - Home';
require_once 'includes/header.php';
?>

<div class="container">
    <!-- 1. Banner Principal (Carrossel) -->
    <section class="hero-banner">
        <?php
        try {
            $banner_stmt = $pdo->query("SELECT image_path, link_url, title FROM banners WHERE is_active = TRUE ORDER BY id DESC");
            $banners = $banner_stmt->fetchAll();

            if (count($banners) > 0) {
                echo '<div class="banner-carousel">';
                foreach ($banners as $index => $banner) {
                    $active_class = ($index == 0) ? 'active' : '';
                    echo '<div class="banner-slide ' . $active_class . '">';
                    if (!empty($banner['link_url'])) {
                        echo '<a href="' . htmlspecialchars($banner['link_url']) . '">';
                    }
                    echo '<img src="' . htmlspecialchars($banner['image_path']) . '" alt="' . htmlspecialchars($banner['title'] ?? 'Banner') . '">';
                    if (!empty($banner['link_url'])) {
                        echo '</a>';
                    }
                    echo '</div>';
                }
                echo '</div>';
                if (count($banners) > 1) {
                    echo '<div class="carousel-nav"><button id="prevBtn">&lt;</button><button id="nextBtn">&gt;</button></div>';
                }
            } else {
                echo '<div class="banner-placeholder"><p>Adicione banners no painel de administração.</p></div>';
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo '<div class="banner-placeholder"><p>Erro ao carregar banners.</p></div>';
        }
        ?>
    </section>

    <!-- 2. Produtos em Destaque -->
    <section class="featured-products">
        <h2 class="section-title">Produtos em Destaque</h2>
        <div class="product-grid">
            <?php
            // Lógica para buscar produtos em destaque do banco de dados
            try {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE is_featured = TRUE LIMIT 4");
                $stmt->execute();
                $featured_products = $stmt->fetchAll();

                if (count($featured_products) > 0) {
                    foreach ($featured_products as $product) {
                        // Placeholder para o card do produto
                        echo '<div class="product-card">';
                        echo '  <a href="produto_detalhe.php?id=' . htmlspecialchars($product['id']) . '">';
                        // A imagem principal será buscada da tabela product_images
                        echo '      <img src="https://via.placeholder.com/300x300?text=' . urlencode($product['name']) . '" alt="' . htmlspecialchars($product['name']) . '">';
                        echo '      <div class="product-info">';
                        echo '          <h3>' . htmlspecialchars($product['name']) . '</h3>';
                        echo '          <p class="price">R$ ' . number_format($product['price'], 2, ',', '.') . '</p>';
                        echo '      </div>';
                        echo '  </a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Nenhum produto em destaque no momento.</p>';
                }
            } catch (PDOException $e) {
                echo '<p>Erro ao carregar produtos. Tente novamente mais tarde.</p>';
                error_log($e->getMessage());
            }
            ?>
        </div>
    </section>
</div>

<?php
require_once 'includes/footer.php';
?>
