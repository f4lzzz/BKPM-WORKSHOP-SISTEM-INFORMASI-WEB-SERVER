
<?php
$title = 'Edit Mahasiswa';

$old = static function (string $key) use ($data): string {
    return htmlspecialchars((string) ($data[$key] ?? ''), ENT_QUOTES, 'UTF-8');
};

ob_start();
?>

<div class="mb-4">
    <h2 class="fw-bold">Edit Mahasiswa</h2>
    <p class="text-muted">Perbarui informasi mahasiswa.</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/mahasiswa/update" method="POST">

            <input
                type="hidden"
                name="id"
                value="<?= (int) ($data['id'] ?? 0) ?>"
            >

            <div class="mb-3">
                <label class="form-label">NIM</label>
                <input
                    type="text"
                    name="nim"
                    class="form-control"
                    value="<?= $old('nim') ?>"
                    required
                    inputmode="numeric"
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Mahasiswa</label>
                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    value="<?= $old('nama') ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?= $old('email') ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Program Studi</label>
                <select name="prodi_id" class="form-select" required>
                    <option value="">-- Pilih Program Studi --</option>
                    <?php foreach ($prodi as $p): ?>
                        <option
                            value="<?= (int) $p['id'] ?>"
                            <?= (string) ($data['prodi_id'] ?? '') === (string) $p['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($p['nama'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Angkatan</label>
                <input
                    type="number"
                    name="angkatan"
                    class="form-control"
                    value="<?= $old('angkatan') ?>"
                    min="2000"
                    max="2100"
                    required
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Update
                </button>
                <a href="<?= BASE_URL ?>/" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>