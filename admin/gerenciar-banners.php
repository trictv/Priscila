<?php
$admin_page_title = 'Gerenciar Banners';
require_once 'includes/header.php';

// --- Lógica para Adicionar e Excluir Banners ---
$upload_dir = '../assets/uploads/banners/';

// Lógica para Salvar um novo banner
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['banner_image'])) {
    if ($_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
        $link_url = trim($_POST['link_url'] ?? '');

        $file_name = 'banner_' . time() . '_' . basename($_FILES['banner_image']['name']);
        $target_path = $upload_dir . $file_name;
        $db_path = 'assets/uploads/banners/' . $file_name;

        $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (in_array($_FILES['banner_image']['type'], $allowed_types)) {
            if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $target_path)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO banners (image_path, link_url) VALUES (?, ?)");
                    $stmt->execute([$db_path, $link_url]);
                    echo "<div class='alert alert-success'>Banner adicionado com sucesso!</div>";
                } catch (PDOException $e) {
                    echo "<div class='alert alert-danger'>Erro ao salvar banner no banco de dados: " . $e->getMessage() . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Falha ao mover o arquivo enviado.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Tipo de arquivo inválido.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Ocorreu um erro no upload do arquivo.</div>";
    }
}

// Lógica para Deletar um banner
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $banner_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($banner_id) {
        try {
            // Busca o caminho da imagem para excluir o arquivo
            $stmt = $pdo->prepare("SELECT image_path FROM banners WHERE id = ?");
            $stmt->execute([$banner_id]);
            $image_path_to_delete = $stmt->fetchColumn();

            // Deleta o registro do banco
            $delete_stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
            $delete_stmt->execute([$banner_id]);

            // Deleta o arquivo do servidor
            if ($image_path_to_delete && file_exists('../' . $image_path_to_delete)) {
                unlink('../' . $image_path_to_delete);
            }

            echo "<div class='alert alert-success'>Banner excluído com sucesso!</div>";
            echo "<script>window.location.href = 'gerenciar-banners.php';</script>";
            exit;
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Erro ao excluir o banner: " . $e->getMessage() . "</div>";
        }
    }
}

?>

<!-- Formulário para Adicionar Novo Banner -->
<div class="card">
    <div class="card-header">
        <h3>Adicionar Novo Banner</h3>
    </div>
    <div class="card-body">
        <form action="gerenciar-banners.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="banner_image">Imagem do Banner (Recomendado: 1200x450px)</label>
                <input type="file" id="banner_image" name="banner_image" class="form-control" required accept="image/*">
            </div>
            <div class="form-group">
                <label for="link_url">URL de Destino (opcional)</label>
                <input type="url" id="link_url" name="link_url" class="form-control" placeholder="https://exemplo.com/produto/123">
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Banner</button>
        </form>
    </div>
</div>

<!-- Lista de Banners Atuais -->
<div class="card">
    <div class="card-header">
        <h3>Banners Atuais</h3>
    </div>
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>Pré-visualização</th>
                    <th>Caminho da Imagem</th>
                    <th>Link de Destino</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $stmt = $pdo->query("SELECT id, image_path, link_url FROM banners ORDER BY id DESC");
                    $banners = $stmt->fetchAll();

                    if (count($banners) > 0) {
                        foreach ($banners as $banner) {
                            echo "<tr>";
                            echo "<td><img src='../" . htmlspecialchars($banner['image_path']) . "' width='200' alt='Banner'></td>";
                            echo "<td>" . htmlspecialchars($banner['image_path']) . "</td>";
                            echo "<td>" . ($banner['link_url'] ? '<a href="' . htmlspecialchars($banner['link_url']) . '" target="_blank">' . htmlspecialchars($banner['link_url']) . '</a>' : 'Nenhum') . "</td>";
                            echo "<td><a href='gerenciar-banners.php?action=delete&id=" . $banner['id'] . "' class='btn btn-danger' onclick='return confirm(\"Tem certeza que deseja excluir este banner?\")'>Excluir</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Nenhum banner cadastrado.</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='4'>Erro ao buscar banners: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
