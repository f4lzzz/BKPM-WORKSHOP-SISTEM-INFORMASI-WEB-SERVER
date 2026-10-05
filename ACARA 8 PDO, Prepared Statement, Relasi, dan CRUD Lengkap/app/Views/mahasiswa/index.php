
<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$keyword = $_GET['search'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Data Mahasiswa</h2>
            <p class="text-muted mb-0">Kelola data mahasiswa dan program studi.</p>
        </div>

        <a href="<?= BASE_URL ?>/mahasiswa/create"
           class="btn btn-primary">
            + Tambah Mahasiswa
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form method="GET"
                  action="<?= BASE_URL ?>/mahasiswa"
                  class="row g-2 mb-4">

                <div class="col-md-9">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari NIM atau nama mahasiswa..."
                           value="<?= e($keyword) ?>">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        Cari
                    </button>

                    <a href="<?= BASE_URL ?>/mahasiswa"
                       class="btn btn-outline-secondary w-100">
                        Reset
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($mahasiswa)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Data mahasiswa tidak ditemukan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; ?>
                            <?php foreach ($mahasiswa as $m): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= e($m['nim']) ?></td>
                                    <td><?= e($m['nama']) ?></td>
                                    <td><?= e($m['email'] ?? '-') ?></td>
                                    <td><?= e($m['nama_prodi'] ?? '-') ?></td>
                                    <td><?= e($m['angkatan']) ?></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="<?= BASE_URL ?>/mahasiswa/edit?id=<?= e($m['id']) ?>"
                                               class="btn btn-sm btn-warning">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                  action="<?= BASE_URL ?>/mahasiswa/destroy"
                                                  onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini?')">
                                                <input type="hidden"
                                                       name="id"
                                                       value="<?= e($m['id']) ?>">

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-muted small">
                Jumlah data ditampilkan: <?= count($mahasiswa) ?> mahasiswa
            </div>

        </div>
    </div>

</div>

</body>
</html>