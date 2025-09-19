<?php
// Lógica para determinar o título da página
$page_title = 'Todos os Produtos';
$category_filter = null;
if (isset($_GET['categoria'])) {
    // Busca o ID da categoria pelo nome para uma filtragem mais robusta
    // Esta parte pode ser melhorada para usar slugs em vez de nomes diretos
    // Por enquanto, vamos filtrar pelo nome para simplicidade
    $category_name = filter_input(INPUT_GET, 'categoria', FILTER_SANITIZE_STRING);
    $page_title = 'Produtos - ' . htmlspecialchars(ucfirst($category_name));

    // Precisamos do ID da categoria para filtrar os produtos
    try {
        $cat_stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
        $cat_stmt->execute([$category_name]);
        $category = $cat_stmt->fetch();
        if ($category) {
            $category_filter = $category['id'];
        }
    } catch (PDOException $e) {
        error_log("Erro ao buscar categoria: " . $e->getMessage());
    }
}

require_once 'includes/header.php';
?>

<div class="container">
    <section class="product-listing">
        <h1 class="section-title"><?php echo htmlspecialchars($page_title); ?></h1>

        <!-- Barra de Filtros (simples por enquanto) -->
        <aside class="filters">
            <h4>Categorias</h4>
            <ul>
                <li><a href="produtos.php">Todas</a></li>
                <?php
                // Busca todas as categorias para o menu de filtros
                try {
                    $all_cat_stmt = $pdo->query("SELECT name FROM categories ORDER BY name ASC");
                    while ($row = $all_cat_stmt->fetch()) {
                        echo '<li><a href="produtos.php?categoria=' . urlencode($row['name']) . '">' . htmlspecialchars($row['name']) . '</a></li>';
                    }
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                }
                ?>
            </ul>
        </aside>

        <!-- Grade de Produtos -->
        <div class="product-grid">
            <?php
            try {
                // Monta a query base
                $sql = "SELECT p.id, p.name, p.price, p.sale_price,
                               (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = TRUE LIMIT 1) as primary_image
                        FROM products p";

                // Adiciona o filtro de categoria se existir
                if ($category_filter) {
                    $sql .= " WHERE p.category_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$category_filter]);
                } else {
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                }

                $products = $stmt->fetchAll();

                if (count($products) > 0) {
                    foreach ($products as $product) {
                        $image_path = $product['primary_image'] ? $base_url . $product['primary_image'] : 'https://via.placeholder.com/300x300?text=Sem+Imagem';

                        echo '<div class="product-card">';
                        echo '  <a href="produto_detalhe.php?id=' . htmlspecialchars($product['id']) . '">';
                        echo '      <div class="product-image">';
                        echo '          <img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($product['name']) . '">';
                        if ($product['sale_price']) {
                            echo '<span class="sale-tag">Promoção</span>';
                        }
                        echo '      </div>';
                        echo '      <div class="product-info">';
                        echo '          <h3>' . htmlspecialchars($product['name']) . '</h3>';
                        echo '          <div class="price-container">';
                        if ($product['sale_price']) {
                            echo '              <span class="sale-price">R$ ' . number_format($product['sale_price'], 2, ',', '.') . '</span>';
                            echo '              <span class="original-price">R$ ' . number_format($product['price'], 2, ',', '.') . '</span>';
                        } else {
                            echo '              <span class="price">R$ ' . number_format($product['price'], 2, ',', '.') . '</span>';
                        }
                        echo '          </div>';
                        echo '      </div>';
                        echo '  </a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Nenhum produto encontrado nesta categoria.</p>';
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
