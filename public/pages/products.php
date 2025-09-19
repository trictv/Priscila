<?php
// This file is included by the main index.php
include __DIR__ . '/../partials/header.php';

$category_name = $_GET['cat'] ?? null;
$filter = $_GET['filter'] ?? null;

$page_title = "Todos os Produtos";
if ($category_name) {
    $page_title = "Categoria: " . htmlspecialchars(ucfirst($category_name));
} elseif ($filter === 'promocoes') {
    $page_title = "Promoções";
}

?>

<div class="products-page">
    <h1><?php echo $page_title; ?></h1>

    <aside class="filters">
        <h3>Filtros</h3>
        <!-- Filter options will be implemented later -->
        <p>Placeholder for category and size filters.</p>
    </aside>

    <section class="product-grid">
        <!-- Products will be loaded from the database based on filters -->
        <p>Placeholder for the product grid. Products will be displayed here.</p>
    </section>
</div>

<?php
include __DIR__ . '/../partials/footer.php';
?>
