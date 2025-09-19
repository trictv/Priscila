<?php
require_once 'includes/db_connect.php'; // Conexão primeiro para usar $pdo

// 1. Validar e buscar o ID do produto
$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$product_id) {
    // Redireciona se o ID for inválido ou não existir
    header("Location: produtos.php");
    exit;
}

try {
    // 2. Buscar detalhes do produto principal
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    // Se o produto não for encontrado, redireciona
    if (!$product) {
        header("Location: produtos.php");
        exit;
    }

    // 3. Buscar todas as imagens do produto
    $img_stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC");
    $img_stmt->execute([$product_id]);
    $images = $img_stmt->fetchAll(PDO::FETCH_COLUMN);

    // 4. Buscar os tamanhos disponíveis
    $size_stmt = $pdo->prepare("SELECT size FROM product_sizes WHERE product_id = ? ORDER BY size ASC");
    $size_stmt->execute([$product_id]);
    $sizes = $size_stmt->fetchAll(PDO::FETCH_COLUMN);

    // 5. Buscar produtos sugeridos (da mesma categoria, excluindo o atual)
    $suggested_stmt = $pdo->prepare(
        "SELECT id, name, price, sale_price,
        (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = TRUE LIMIT 1) as primary_image
        FROM products p WHERE category_id = ? AND id != ? LIMIT 4"
    );
    $suggested_stmt->execute([$product['category_id'], $product_id]);
    $suggested_products = $suggested_stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Erro na página de detalhes do produto: " . $e->getMessage());
    die("Ocorreu um erro ao carregar o produto. Tente novamente.");
}

// Define o título da página com o nome do produto
$page_title = htmlspecialchars($product['name']) . ' - BEEFIT';
require_once 'includes/header.php';

// 6. Construir link do WhatsApp
$whatsapp_number = preg_replace('/[^0-9]/', '', $settings['whatsapp_number']);
$message = urlencode("Olá, BEEFIT! Tenho interesse no produto: {$product['name']} - Ref: {$product['reference_code']}.");
$whatsapp_link = "https://wa.me/{$whatsapp_number}?text={$message}";
?>

<div class="container product-detail-page">
    <div class="product-main-content">
        <!-- Galeria de Imagens -->
        <div class="product-gallery">
            <div class="main-image">
                <img src="<?php echo !empty($images) ? $base_url . htmlspecialchars($images[0]) : 'https://via.placeholder.com/500x500?text=Sem+Imagem'; ?>" alt="Imagem principal de <?php echo htmlspecialchars($product['name']); ?>" id="mainProductImage">
            </div>
            <?php if (count($images) > 1): ?>
            <div class="thumbnail-images">
                <?php foreach ($images as $image): ?>
                <img src="<?php echo $base_url . htmlspecialchars($image); ?>" alt="Miniatura de <?php echo htmlspecialchars($product['name']); ?>" class="thumbnail">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Informações do Produto -->
        <div class="product-information">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="reference-code">Ref: <?php echo htmlspecialchars($product['reference_code']); ?></p>

            <div class="price-container">
                <?php if ($product['sale_price']): ?>
                    <span class="sale-price">R$ <?php echo number_format($product['sale_price'], 2, ',', '.'); ?></span>
                    <span class="original-price">R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></span>
                <?php else: ?>
                    <span class="price">R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></span>
                <?php endif; ?>
            </div>

            <?php if (!empty($sizes)): ?>
            <div class="sizes-available">
                <h3>Tamanhos Disponíveis:</h3>
                <div class="size-tags">
                    <?php foreach ($sizes as $size): ?>
                    <span><?php echo htmlspecialchars($size); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="product-description">
                <h3>Descrição do Produto</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>

            <a href="<?php echo $whatsapp_link; ?>" class="btn-whatsapp" target="_blank">
                Chamar no WhatsApp
            </a>
        </div>
    </div>

    <!-- Produtos Sugeridos -->
    <?php if (!empty($suggested_products)): ?>
    <section class="suggested-products">
        <h2 class="section-title">Você também pode gostar</h2>
        <div class="product-grid">
            <?php foreach ($suggested_products as $suggested):
                $suggested_image = $suggested['primary_image'] ? $base_url . $suggested['primary_image'] : 'https://via.placeholder.com/300x300?text=Sem+Imagem';
            ?>
                <div class="product-card">
                    <a href="produto_detalhe.php?id=<?php echo $suggested['id']; ?>">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($suggested_image); ?>" alt="<?php echo htmlspecialchars($suggested['name']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($suggested['name']); ?></h3>
                            <div class="price-container">
                                <?php if ($suggested['sale_price']): ?>
                                    <span class="sale-price">R$ <?php echo number_format($suggested['sale_price'], 2, ',', '.'); ?></span>
                                    <span class="original-price">R$ <?php echo number_format($suggested['price'], 2, ',', '.'); ?></span>
                                <?php else: ?>
                                    <span class="price">R$ <?php echo number_format($suggested['price'], 2, ',', '.'); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
?>
