<?php

$flash = $_SESSION['flash'] ?? null;

if ($flash !== null) {
    unset($_SESSION['flash']);
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Sistem Informasi Akademik</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<nav class="navbar navbar-dark bg-primary">

    <div class="container">

        <a
            class="navbar-brand"
            href="<?= BASE_URL ?>/mahasiswa"
        >
            Sistem Informasi Akademik
        </a>

    </div>

</nav>

<div class="container py-4">

    <?php if ($flash): ?>

        <div
            class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show"
            role="alert"
        >

            <?= e($flash['message']) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    <?php endif; ?>

    <?= $content ?? '' ?>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>