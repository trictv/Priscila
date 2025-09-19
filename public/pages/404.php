<?php
// This file is included by the main index.php for unknown pages
include __DIR__ . '/../partials/header.php';
?>

<div class="not-found-page" style="text-align: center; padding: 50px 0;">
    <h1>Página não encontrada</h1>
    <p>Desculpe, a página que você está procurando não existe.</p>
    <p>
        <a href="index.php?page=home" class="btn btn-primary" style="text-decoration: none; padding: 10px 20px; color: white; display: inline-block; margin-top: 20px;">Voltar para a Home</a>
    </p>
</div>

<?php
include __DIR__ . '/../partials/footer.php';
?>
