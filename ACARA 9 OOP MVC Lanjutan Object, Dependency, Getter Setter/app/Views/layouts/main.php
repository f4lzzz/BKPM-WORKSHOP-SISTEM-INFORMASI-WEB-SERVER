
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Acara 9 - Data Mahasiswa') ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/">
            ACARA 9 - OOP MVC
        </a>
        <span class="navbar-text text-white">
            Manajemen Data Mahasiswa
        </span>
    </div>
</nav>

<main class="container py-4">
    <?= $content ?? '' ?>
</main>

<footer class="text-center text-muted py-3">
    <small>Praktikum Workshop Sistem Informasi Web Server</small>
</footer>

</body>
</html>