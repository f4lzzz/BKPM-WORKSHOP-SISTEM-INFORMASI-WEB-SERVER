<?php

ob_start();

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2>Data Mahasiswa</h2>

        <p class="text-muted mb-0">
            Manajemen data mahasiswa
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

        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">

                <thead class="table-primary">

                    <tr>
                        <th width="60">No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Angkatan</th>
                        <th width="180">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($mahasiswa)): ?>

                    <tr>
                        <td colspan="6" class="text-center">
                            Belum ada data mahasiswa.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($mahasiswa as $index => $m): ?>

                        <tr>

                            <td>
                                <?= $index + 1 ?>
                            </td>

                            <td>
                                <?= htmlspecialchars((string) $m['nim'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars((string) $m['nama'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars((string) $m['email'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars((string) $m['angkatan'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>

                                <a
                                    href="<?= BASE_URL ?>/mahasiswa/edit?id=<?= htmlspecialchars((string) $m['id'], ENT_QUOTES, 'UTF-8') ?>"
                                    class="btn btn-warning btn-sm"
                                >
                                    Edit
                                </a>

                                <form
                                    action="<?= BASE_URL ?>/mahasiswa/delete/<?= htmlspecialchars((string) $m['id'], ENT_QUOTES, 'UTF-8') ?>"
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

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layouts/main.php';