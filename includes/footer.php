</main> <!-- Fecha a tag <main> aberta no header.php -->

<footer class="main-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-about">
                <h4>Sobre a BEEFIT</h4>
                <p>Estilo e sofisticação em cada detalhe. Encontre as melhores tendências da moda e calçados para complementar o seu visual.</p>
            </div>
            <div class="footer-nav">
                <h4>Links Rápidos</h4>
                <ul>
                    <li><a href="<?php echo $base_url; ?>index.php">Home</a></li>
                    <li><a href="<?php echo $base_url; ?>produtos.php">Produtos</a></li>
                    <li><a href="<?php echo $base_url; ?>sobre.php">Sobre Nós</a></li>
                    <li><a href="<?php echo $base_url; ?>admin/login.php">Admin</a></li>
                </ul>
            </div>
            <div class="footer-social">
                <h4>Nossas Redes</h4>
                <div class="social-icons">
                    <?php if (!empty($settings['social_instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['social_instagram']); ?>" target="_blank" title="Instagram">
                            <!-- Ícone de Instagram (pode ser substituído por SVG ou Font Awesome) -->
                            <img src="https://img.icons8.com/ios-filled/50/000000/instagram-new.png" alt="Instagram">
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_facebook'])): ?>
                        <a href="<?php echo htmlspecialchars($settings['social_facebook']); ?>" target="_blank" title="Facebook">
                            <!-- Ícone de Facebook -->
                            <img src="https://img.icons8.com/ios-filled/50/000000/facebook.png" alt="Facebook">
                        </a>
                    <?php endif; ?>
                    <!-- Adicionar outros ícones se necessário -->
                </div>
            </div>
            <div class="footer-contact">
                <h4>Contato</h4>
                <?php if (!empty($settings['whatsapp_number'])):
                    $whatsapp_link = "https://wa.me/" . preg_replace('/[^0-9]/', '', $settings['whatsapp_number']);
                ?>
                    <p><strong>WhatsApp:</strong> <a href="<?php echo $whatsapp_link; ?>" target="_blank"><?php echo htmlspecialchars($settings['whatsapp_number']); ?></a></p>
                <?php endif; ?>
                <p><strong>Endereço:</strong> Rua Exemplo, 123, Cidade, Estado</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> BEEFIT. Todos os direitos reservados. Site desenvolvido com ❤️.</p>
        </div>
    </div>
</footer>

<!-- JavaScript Principal -->
<script src="<?php echo $base_url; ?>assets/js/scripts.js"></script>

</body>
</html>
