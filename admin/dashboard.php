<?php
// This file is included by index.php, which handles session and authentication.
// The header partial also checks for login status.
include 'partials/header.php';
?>

<div class="card">
    <h3>Welcome to your Dashboard</h3>
    <p>From here, you can manage all aspects of your BEEFIT online catalog.</p>
    <p>Use the menu on the left to navigate through the different management sections, such as:</p>
    <ul>
        <li><strong>Products:</strong> Add, edit, or remove items from your catalog.</li>
        <li><strong>Categories:</strong> Organize your products into different categories.</li>
        <li><strong>Site Settings:</strong> Update your logo, colors, and social media links.</li>
        <li><strong>Home Banner:</strong> Change the main image on your homepage.</li>
    </ul>
</div>

<?php
include 'partials/footer.php';
?>
