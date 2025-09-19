<?php
// This file is included by the main index.php
include __DIR__ . '/../partials/header.php';
?>

<div class="home-page">
    <section class="hero-banner">
        <!-- Banner image will be loaded from the database -->
        <img src="https://via.placeholder.com/1200x400.png?text=BEEFIT+Coleção+Nova" alt="Main Banner" style="width:100%;">
    </section>

    <section class="featured-products">
        <h2>Produtos em Destaque</h2>
        <div class="product-grid">
            <!-- Featured products will be loaded from the database -->
            <p>Placeholder for 4-8 featured products...</p>
        </div>
    </section>

    <section class="cta-section">
        <h2>Veja nossa nova coleção</h2>
        <a href="index.php?page=products" class="cta-button">Ver todos os produtos</a>
    </section>
</div>

<?php
include __DIR__ . '/../partials/footer.php';
?>
