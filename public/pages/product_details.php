<?php
// This file is included by the main index.php
include __DIR__ . '/../partials/header.php';

// In a real scenario, we'd get the product ID from the URL
// e.g., index.php?page=product&id=123
$product_id = $_GET['id'] ?? 'XYZ';
$product_name = "Nome do Produto Exemplo"; // Placeholder
$reference_code = "REF12345"; // Placeholder

// This creates the pre-filled WhatsApp message
$whatsapp_message = urlencode("Olá, BEEFIT! Tenho interesse no produto: {$product_name} - Ref: {$reference_code}. Poderia me passar mais informações?");
$whatsapp_number = '5517999999999'; // This will be fetched from settings later
$whatsapp_url = "https://wa.me/{$whatsapp_number}?text={$whatsapp_message}";

?>

<div class="product-details-page">
    <div class="gallery">
        <!-- Main image and thumbnails will be loaded from the database -->
        <img src="https://via.placeholder.com/600x600.png?text=Produto" alt="Product Image" style="max-width:100%;">
        <div class="thumbnails">
            <!-- Thumbnails here -->
        </div>
    </div>

    <div class="product-info">
        <h1><?php echo htmlspecialchars($product_name); ?></h1>
        <p class="reference-code">Ref: <?php echo htmlspecialchars($reference_code); ?></p>
        <p class="price">R$ 199,90</p>

        <div class="sizes">
            <h3>Tamanhos Disponíveis:</h3>
            <span>P</span> <span>M</span> <span>G</span>
        </div>

        <div class="description">
            <h3>Descrição</h3>
            <p>Placeholder for detailed product description. Information about material, fit, and other features will be displayed here.</p>
        </div>

        <a href="<?php echo $whatsapp_url; ?>" target="_blank" class="cta-button">
            TENHO INTERESSE, CHAMAR NO WHATSAPP
        </a>
    </div>
</div>

<div class="suggested-products">
    <h2>Você também pode gostar</h2>
    <!-- Suggested products will be loaded from the database -->
    <p>Placeholder for suggested products.</p>
</div>

<?php
include __DIR__ . '/../partials/footer.php';
?>
