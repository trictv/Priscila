<?php
$admin_page_title = 'Configurações do Site';
require_once 'includes/header.php'; // Inclui o cabeçalho, proteção e conexão com o BD

// --- Lógica para Salvar as Configurações ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $settings_to_save = $_POST['settings'];

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");

        foreach ($settings_to_save as $key => $value) {
            $stmt->execute([trim($value), $key]);
        }

        // --- Lógica de Upload do Logo ---
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/images/';
            // Garante que o nome do arquivo seja seguro e único
            $file_name = 'logo_' . time() . '_' . basename($_FILES['site_logo']['name']);
            $target_path = $upload_dir . $file_name;
            $db_path = 'assets/images/' . $file_name;

            // Valida o tipo de arquivo
            $allowed_types = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];
            if (in_array($_FILES['site_logo']['type'], $allowed_types)) {
                // Remove o logo antigo, se existir
                $old_logo_path = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'site_logo'")->fetchColumn();
                if ($old_logo_path && file_exists('../' . $old_logo_path)) {
                    unlink('../' . $old_logo_path);
                }

                // Move o novo arquivo
                if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $target_path)) {
                    $stmt->execute([$db_path, 'site_logo']);
                } else {
                    throw new Exception("Falha ao mover o arquivo de logo.");
                }
            } else {
                throw new Exception("Tipo de arquivo de logo inválido.");
            }
        }

        $pdo->commit();
        echo "<div class='alert alert-success'>Configurações salvas com sucesso! A página será recarregada.</div>";
        echo "<script>setTimeout(() => window.location.href = 'configuracoes.php', 1500);</script>";
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div class='alert alert-danger'>Erro ao salvar configurações: " . $e->getMessage() . "</div>";
    }
}


// --- Busca as configurações atuais para exibir no formulário ---
// A variável $settings já foi carregada em db_connect.php, que está no header.php
?>

<div class="card">
    <div class="card-body">
        <form action="configuracoes.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="site_logo">Logo do Site</label>
                <input type="file" id="site_logo" name="site_logo" class="form-control" accept="image/*">
                <?php if (!empty($settings['site_logo'])): ?>
                    <div class="current-logo">
                        <p>Logo Atual:</p>
                        <img src="../<?php echo htmlspecialchars($settings['site_logo']); ?>" alt="Logo Atual" style="max-height: 80px; background: #f0f0f0; padding: 10px; border-radius: 5px; margin-top: 10px;">
                    </div>
                <?php endif; ?>
            </div>

            <hr>

            <div class="form-group">
                <label for="whatsapp_number">Número do WhatsApp</label>
                <input type="text" id="whatsapp_number" name="settings[whatsapp_number]" class="form-control" value="<?php echo htmlspecialchars($settings['whatsapp_number'] ?? ''); ?>">
            </div>

            <hr>

            <h4>Cores do Site</h4>
            <div class="form-row">
                <div class="form-group">
                    <label for="title_color">Cor dos Títulos</label>
                    <input type="color" id="title_color" name="settings[title_color]" class="form-control" value="<?php echo htmlspecialchars($settings['title_color'] ?? '#1a1a1a'); ?>">
                </div>
                <div class="form-group">
                    <label for="button_color">Cor dos Botões e Destaques</label>
                    <input type="color" id="button_color" name="settings[button_color]" class="form-control" value="<?php echo htmlspecialchars($settings['button_color'] ?? '#D4AF37'); ?>">
                </div>
            </div>

            <hr>

            <h4>Links das Redes Sociais</h4>
            <div class="form-group">
                <label for="social_instagram">URL do Instagram</label>
                <input type="url" id="social_instagram" name="settings[social_instagram]" class="form-control" value="<?php echo htmlspecialchars($settings['social_instagram'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="social_facebook">URL do Facebook</label>
                <input type="url" id="social_facebook" name="settings[social_facebook]" class="form-control" value="<?php echo htmlspecialchars($settings['social_facebook'] ?? ''); ?>">
            </div>
            <!-- Adicionar outros campos de redes sociais se necessário -->

            <button type="submit" name="save_settings" class="btn btn-primary">Salvar Configurações</button>
        </form>
    </div>
</div>

<style>
    .form-control { width: 100%; padding: 8px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #ccc; }
    .form-row { display: flex; gap: 20px; }
    .form-row .form-group { flex: 1; }
    input[type="color"] { padding: 0; height: 40px; }
    hr { margin: 30px 0; border: 0; border-top: 1px solid #eee; }
</style>

<?php
require_once 'includes/footer.php';
?>
