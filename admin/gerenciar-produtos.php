<?php
$admin_page_title = 'Gerenciar Produtos';
require_once 'includes/header.php';

// --- Lógica de Ações (Adicionar, Editar, Excluir) ---
// Esta seção será expandida nas próximas etapas.
$action = $_GET['action'] ?? 'list';
$product_id = $_GET['id'] ?? null;

// Lógica para deletar um produto
if ($action === 'delete' && $product_id) {
    // Adicionar verificação de segurança (token CSRF) seria ideal em um projeto real.
    try {
        // Primeiro, buscar os caminhos das imagens para excluí-las do servidor
        $img_stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
        $img_stmt->execute([$product_id]);
        $images_to_delete = $img_stmt->fetchAll(PDO::FETCH_COLUMN);

        // Excluir o produto do banco (ON DELETE CASCADE cuidará das tabelas relacionadas)
        $delete_stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $delete_stmt->execute([$product_id]);

        // Excluir os arquivos de imagem do servidor
        foreach ($images_to_delete as $image_path) {
            if (file_exists('../' . $image_path)) {
                unlink('../' . $image_path);
            }
        }

        echo "<div class='alert alert-success'>Produto excluído com sucesso!</div>";
        // Redireciona para a lista para evitar re-submissão
        echo "<script>window.location.href = 'gerenciar-produtos.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erro ao excluir o produto: " . $e->getMessage() . "</div>";
    }
}

// Lógica para processar o formulário de Adicionar/Editar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    // --- Lógica para Salvar (Criar/Atualizar) Produto ---
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $name = trim($_POST['name']);
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
    $reference_code = trim($_POST['reference_code']);
    $description = trim($_POST['description']);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $sale_price = filter_input(INPUT_POST, 'sale_price', FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE) ?: null;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $sizes_str = trim($_POST['sizes']);

    // Validação simples
    if (empty($name) || empty($category_id) || empty($price)) {
        echo "<div class='alert alert-danger'>Nome, Categoria e Preço são campos obrigatórios.</div>";
    } else {
        try {
            $pdo->beginTransaction();

            // Se for um novo produto (INSERT) ou um produto existente (UPDATE)
            if ($product_id) { // UPDATE
                $stmt = $pdo->prepare(
                    "UPDATE products SET name = ?, category_id = ?, reference_code = ?, description = ?, price = ?, sale_price = ?, is_featured = ? WHERE id = ?"
                );
                $stmt->execute([$name, $category_id, $reference_code, $description, $price, $sale_price, $is_featured, $product_id]);
            } else { // INSERT
                $stmt = $pdo->prepare(
                    "INSERT INTO products (name, category_id, reference_code, description, price, sale_price, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->execute([$name, $category_id, $reference_code, $description, $price, $sale_price, $is_featured]);
                $product_id = $pdo->lastInsertId(); // Pega o ID do novo produto
            }

            // --- Gerenciar Tamanhos ---
            // Primeiro, remove todos os tamanhos antigos para simplificar
            $pdo->prepare("DELETE FROM product_sizes WHERE product_id = ?")->execute([$product_id]);
            if (!empty($sizes_str)) {
                $sizes = array_map('trim', explode(',', $sizes_str));
                $size_stmt = $pdo->prepare("INSERT INTO product_sizes (product_id, size) VALUES (?, ?)");
                foreach ($sizes as $size) {
                    if (!empty($size)) {
                        $size_stmt->execute([$product_id, $size]);
                    }
                }
            }

            // --- Gerenciar Upload de Imagens ---
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $upload_dir = '../assets/uploads/products/';
                $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];

                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                        $file_type = $_FILES['images']['type'][$key];
                        if (in_array($file_type, $allowed_types)) {
                            $file_name = uniqid('prod_') . '-' . basename($_FILES['images']['name'][$key]);
                            $target_path = $upload_dir . $file_name;

                            if (move_uploaded_file($tmp_name, $target_path)) {
                                $db_path = 'assets/uploads/products/' . $file_name;
                                // Verifica se já existe uma imagem primária
                                $check_primary = $pdo->prepare("SELECT COUNT(*) FROM product_images WHERE product_id = ? AND is_primary = TRUE");
                                $check_primary->execute([$product_id]);
                                $has_primary = $check_primary->fetchColumn() > 0;

                                $is_primary = !$has_primary; // A primeira imagem enviada se torna primária

                                $img_stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)");
                                $img_stmt->execute([$product_id, $db_path, $is_primary]);
                            }
                        }
                    }
                }
            }

            $pdo->commit();
            echo "<div class='alert alert-success'>Produto salvo com sucesso!</div>";
            echo "<script>setTimeout(() => window.location.href = 'gerenciar-produtos.php', 1000);</script>";
            // Força o script a parar aqui para não carregar o resto da página desnecessariamente
            exit;

        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "<div class='alert alert-danger'>Erro ao salvar o produto: " . $e->getMessage() . "</div>";
        }
    }
}

// Lógica para deletar uma imagem individual
if ($action === 'delete_image' && isset($_GET['id'])) {
    $image_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $product_id_redirect = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);

    try {
        $img_stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE id = ?");
        $img_stmt->execute([$image_id]);
        $image_path = $img_stmt->fetchColumn();

        if ($image_path) {
            $pdo->prepare("DELETE FROM product_images WHERE id = ?")->execute([$image_id]);
            if (file_exists('../' . $image_path)) {
                unlink('../' . $image_path);
            }
        }
        echo "<script>window.location.href = 'gerenciar-produtos.php?action=edit&id={$product_id_redirect}';</script>";
        exit;
    } catch (PDOException $e) {
         echo "<div class='alert alert-danger'>Erro ao excluir a imagem: " . $e->getMessage() . "</div>";
    }
}

// --- Exibição do Conteúdo ---
if ($action === 'add' || ($action === 'edit' && $product_id)) {
    // --- Formulário para Adicionar/Editar Produto ---
    $product_data = [
        'id' => null, 'name' => '', 'category_id' => '', 'reference_code' => '',
        'description' => '', 'price' => '', 'sale_price' => '', 'is_featured' => false
    ];
    $product_sizes = '';
    $product_images = [];

    $form_title = 'Adicionar Novo Produto';
    if ($action === 'edit' && $product_id) {
        $form_title = 'Editar Produto';
        try {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product_data = $stmt->fetch();

            $size_stmt = $pdo->prepare("SELECT size FROM product_sizes WHERE product_id = ?");
            $size_stmt->execute([$product_id]);
            $sizes_array = $size_stmt->fetchAll(PDO::FETCH_COLUMN);
            $product_sizes = implode(', ', $sizes_array);

            $img_stmt = $pdo->prepare("SELECT id, image_path FROM product_images WHERE product_id = ?");
            $img_stmt->execute([$product_id]);
            $product_images = $img_stmt->fetchAll();

        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Erro ao carregar dados do produto: " . $e->getMessage() . "</div>";
        }
    }
?>
<div class="card">
    <div class="card-header">
        <h3><?php echo $form_title; ?></h3>
        <a href="gerenciar-produtos.php" class="btn">Voltar para a Lista</a>
    </div>
    <div class="card-body">
        <form action="gerenciar-produtos.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_data['id']); ?>">

            <div class="form-group">
                <label for="name">Nome do Produto</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product_data['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="category_id">Categoria</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="">Selecione uma categoria</option>
                    <?php
                    $cat_stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name");
                    while ($category = $cat_stmt->fetch()) {
                        $selected = ($category['id'] == $product_data['category_id']) ? 'selected' : '';
                        echo "<option value='{$category['id']}' {$selected}>" . htmlspecialchars($category['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="reference_code">Código de Referência (SKU)</label>
                <input type="text" id="reference_code" name="reference_code" class="form-control" value="<?php echo htmlspecialchars($product_data['reference_code']); ?>">
            </div>

            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea id="description" name="description" class="form-control" rows="5"><?php echo htmlspecialchars($product_data['description']); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price">Preço (R$)</label>
                    <input type="number" step="0.01" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($product_data['price']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="sale_price">Preço Promocional (R$) (opcional)</label>
                    <input type="number" step="0.01" id="sale_price" name="sale_price" class="form-control" value="<?php echo htmlspecialchars($product_data['sale_price']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="sizes">Tamanhos (separados por vírgula, ex: P, M, G, 40, 41)</label>
                <input type="text" id="sizes" name="sizes" class="form-control" value="<?php echo htmlspecialchars($product_sizes); ?>">
            </div>

            <div class="form-group form-check">
                <input type="checkbox" id="is_featured" name="is_featured" class="form-check-input" value="1" <?php echo ($product_data['is_featured']) ? 'checked' : ''; ?>>
                <label for="is_featured" class="form-check-label">Marcar como produto em destaque na Home</label>
            </div>

            <div class="form-group">
                <label for="images">Imagens do Produto (multi-seleção permitida)</label>
                <input type="file" id="images" name="images[]" class="form-control" multiple accept="image/jpeg, image/png, image/webp">
                <small>A primeira imagem enviada será a principal.</small>
            </div>

            <?php if (!empty($product_images)): ?>
            <div class="current-images">
                <h4>Imagens Atuais</h4>
                <?php foreach ($product_images as $image): ?>
                <div class="current-image-item">
                    <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" width="100">
                    <a href="gerenciar-produtos.php?action=delete_image&id=<?php echo $image['id']; ?>&product_id=<?php echo $product_id; ?>" onclick="return confirm('Tem certeza?')" class="delete-image-btn">Excluir</a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <button type="submit" name="save_product" class="btn btn-primary">Salvar Produto</button>
        </form>
    </div>
</div>
<style>
    .form-control { width: 100%; padding: 8px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #ccc; }
    .form-row { display: flex; gap: 20px; }
    .form-row .form-group { flex: 1; }
    .form-check { display: flex; align-items: center; gap: 10px; }
    .current-images { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 10px; }
    .current-image-item { position: relative; }
    .delete-image-btn { position: absolute; top: 0; right: 0; background: rgba(255,0,0,0.7); color: white; text-decoration: none; padding: 2px 5px; font-size: 12px; border-radius: 3px; }
</style>

} else {
    // --- Lista de Produtos (Visão Padrão) ---
?>
<div class="card">
    <div class="card-header">
        <h3>Lista de Produtos</h3>
        <a href="gerenciar-produtos.php?action=add" class="btn btn-primary">Adicionar Novo Produto</a>
    </div>
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Destaque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $stmt = $pdo->query(
                        "SELECT p.id, p.name, p.price, p.is_featured, c.name as category_name,
                        (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = TRUE LIMIT 1) as primary_image
                        FROM products p
                        LEFT JOIN categories c ON p.category_id = c.id
                        ORDER BY p.id DESC"
                    );
                    $products = $stmt->fetchAll();

                    if (count($products) > 0) {
                        foreach ($products as $product) {
                            $image_url = $product['primary_image'] ? '../' . htmlspecialchars($product['primary_image']) : 'https://via.placeholder.com/50';
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($product['id']) . "</td>";
                            echo "<td><img src='" . $image_url . "' width='50' alt='Imagem do Produto'></td>";
                            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($product['category_name'] ?? 'N/A') . "</td>";
                            echo "<td>R$ " . number_format($product['price'], 2, ',', '.') . "</td>";
                            echo "<td>" . ($product['is_featured'] ? 'Sim' : 'Não') . "</td>";
                            echo "<td class='actions'>";
                            echo "  <a href='gerenciar-produtos.php?action=edit&id=" . $product['id'] . "' class='btn btn-secondary'>Editar</a>";
                            echo "  <a href='gerenciar-produtos.php?action=delete&id=" . $product['id'] . "' class='btn btn-danger' onclick='return confirm(\"Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.\")'>Excluir</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Nenhum produto encontrado.</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='7'>Erro ao buscar produtos: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<style>
    .card-header { display: flex; justify-content: space-between; align-items: center; }
    .actions a { margin-right: 5px; }
</style>
<?php
} // Fim do 'else' que mostra a lista

require_once 'includes/footer.php';
?>
