<?php

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$title = $title ?? 'Acara 13 - Service Layer';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title) ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <a
            class="navbar-brand"
            href="<?= BASE_URL ?>/mahasiswa"
        >
            Sistem Akademik
        </a>
    </div>
</nav>

<div class="container">

    <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show">
            <?= htmlspecialchars($flash['message']) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    <?php endif; ?>

    <?= $content ?? '' ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>