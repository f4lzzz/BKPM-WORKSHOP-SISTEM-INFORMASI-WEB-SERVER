
<?php
$title = 'Data Mahasiswa';

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Data Mahasiswa</h2>
        <p class="text-muted mb-0">
            Daftar mahasiswa yang tersimpan dalam database.
        </p>
    </div>

    <a href="<?= BASE_URL ?>/mahasiswa/create"
       class="btn btn-primary">
        + Tambah Mahasiswa
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php if (empty($mahasiswa)): ?>
            <div class="alert alert-info mb-0">
                Belum ada data mahasiswa.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mahasiswa as $i => $mhs): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($mhs['nim']) ?></td>
                                <td><?= htmlspecialchars($mhs['nama']) ?></td>
                                <td><?= htmlspecialchars($mhs['email']) ?></td>
                                <td><?= htmlspecialchars($mhs['nama_prodi']) ?></td>
                                <td><?= htmlspecialchars((string) $mhs['angkatan']) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a
                                            href="<?= BASE_URL ?>/mahasiswa/edit?id=<?= (int) $mhs['id'] ?>"
                                            class="btn btn-warning btn-sm"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="<?= BASE_URL ?>/mahasiswa/delete"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                        >
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $mhs['id'] ?>"
                                            >
                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
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
require __DIR__ . '/../layouts/main.php';
?>