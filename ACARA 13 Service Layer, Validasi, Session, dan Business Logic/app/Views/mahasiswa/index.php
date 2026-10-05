<?php

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2>Data Mahasiswa</h2>
        <p class="text-muted mb-0">
            Manajemen data mahasiswa menggunakan Service Layer.
        </p>
    </div>

    <a
        href="<?= BASE_URL ?>/mahasiswa/create"
        class="btn btn-primary"
    >
        + Tambah Mahasiswa
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <?php if (empty($mahasiswa)): ?>

            <div class="alert alert-info mb-0">
                Belum ada data mahasiswa.
            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-primary">
                        <tr>
                            <th width="50">No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($mahasiswa as $index => $mhs): ?>

                        <tr>
                            <td><?= $index + 1 ?></td>

                            <td>
                                <?= e($mhs['nim']) ?>
                            </td>

                            <td>
                                <?= e($mhs['nama']) ?>
                            </td>

                            <td>
                                <?= e($mhs['email']) ?>
                            </td>

                            <td>
                                <?= e($mhs['prodi_nama']) ?>
                            </td>

                            <td>
                                <?= e($mhs['angkatan']) ?>
                            </td>

                            <td>

                                <a
                                    href="<?= BASE_URL ?>/mahasiswa/edit/<?= $mhs['id'] ?>"
                                    class="btn btn-warning btn-sm"
                                >
                                    Edit
                                </a>

                                <form
                                    action="<?= BASE_URL ?>/mahasiswa/delete/<?= $mhs['id'] ?>"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                >
                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                    >
                                        Hapus
                                    </button>
                                </form>

                            </td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>
</div>

<?php
$content = ob_get_clean();

$title = 'Data Mahasiswa';

require __DIR__ . '/../layouts/main.php';
?>