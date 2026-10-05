<?php
require __DIR__ . '/../partials/header.php';
require __DIR__ . '/../partials/navbar.php';
?>

<main class="container py-4">
    <?php
    if (isset($content)) {
        require $content;
    }
    ?>
</main>

<?php
require __DIR__ . '/../partials/footer.php';
?>