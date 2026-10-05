
<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<div class="mb-4">
    <h2 class="fw-bold">Tambah Mahasiswa</h2>
    <p class="text-muted">Masukkan data mahasiswa baru.</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <?= e($error) ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/mahasiswa/store" method="POST">

            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input
                    type="text"
                    class="form-control"
                    id="nim"
                    name="nim"
                    value="<?= e($data['nim'] ?? '') ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Mahasiswa</label>
                <input
                    type="text"
                    class="form-control"
                    id="nama"
                    name="nama"
                    value="<?= e($data['nama'] ?? '') ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    value="<?= e($data['email'] ?? '') ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="prodi_id" class="form-label">
                    Program Studi
                </label>

                <select
                    class="form-select"
                    id="prodi_id"
                    name="prodi_id"
                    required
                >
                    <option value="">-- Pilih Program Studi --</option>

                    <?php foreach ($prodi as $p): ?>
                        <option
                            value="<?= e($p['id']) ?>"
                            <?= (string)($data['prodi_id'] ?? '') === (string)$p['id'] ? 'selected' : '' ?>
                        >
                            <?= e($p['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="angkatan" class="form-label">Angkatan</label>
                <input
                    type="number"
                    class="form-control"
                    id="angkatan"
                    name="angkatan"
                    min="2000"
                    max="2100"
                    value="<?= e($data['angkatan'] ?? '') ?>"
                    required
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="<?= BASE_URL ?>/" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </form>
    </div>
</div>