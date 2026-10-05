<?php

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

ob_start();
?>

<div class="mb-4">
    <h2>Tambah Mahasiswa</h2>
    <p class="text-muted">
        Tambahkan data mahasiswa baru.
    </p>
</div>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger">
        <?= e($errors['general']) ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">

        <form
            action="<?= BASE_URL ?>/mahasiswa"
            method="POST"
        >

            <div class="mb-3">
                <label for="nim" class="form-label">
                    NIM
                </label>

                <input
                    type="text"
                    name="nim"
                    id="nim"
                    class="form-control <?= isset($errors['nim']) ? 'is-invalid' : '' ?>"
                    value="<?= e($old['nim'] ?? '') ?>"
                    placeholder="Masukkan NIM"
                >

                <?php if (isset($errors['nim'])): ?>
                    <div class="invalid-feedback">
                        <?= e($errors['nim']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">
                    Nama
                </label>

                <input
                    type="text"
                    name="nama"
                    id="nama"
                    class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                    value="<?= e($old['nama'] ?? '') ?>"
                    placeholder="Masukkan nama lengkap"
                >

                <?php if (isset($errors['nama'])): ?>
                    <div class="invalid-feedback">
                        <?= e($errors['nama']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                    value="<?= e($old['email'] ?? '') ?>"
                    placeholder="Masukkan email"
                >

                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback">
                        <?= e($errors['email']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="prodi_id" class="form-label">
                    Program Studi
                </label>

                <select
                    name="prodi_id"
                    id="prodi_id"
                    class="form-select <?= isset($errors['prodi_id']) ? 'is-invalid' : '' ?>"
                >
                    <option value="">-- Pilih Program Studi --</option>

                    <?php foreach ($prodi as $item): ?>

                        <option
                            value="<?= e($item['id']) ?>"
                            <?= (($old['prodi_id'] ?? '') == $item['id']) ? 'selected' : '' ?>
                        >
                            <?= e($item['nama']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <?php if (isset($errors['prodi_id'])): ?>
                    <div class="invalid-feedback">
                        <?= e($errors['prodi_id']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="angkatan" class="form-label">
                    Angkatan
                </label>

                <input
                    type="number"
                    name="angkatan"
                    id="angkatan"
                    class="form-control <?= isset($errors['angkatan']) ? 'is-invalid' : '' ?>"
                    value="<?= e($old['angkatan'] ?? '') ?>"
                    placeholder="Contoh: 2026"
                >

                <?php if (isset($errors['angkatan'])): ?>
                    <div class="invalid-feedback">
                        <?= e($errors['angkatan']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Simpan
                </button>

                <a
                    href="<?= BASE_URL ?>/mahasiswa"
                    class="btn btn-secondary"
                >
                    Kembali
                </a>

            </div>

        </form>

    </div>
</div>

<?php
$content = ob_get_clean();

$title = 'Tambah Mahasiswa';

require __DIR__ . '/../layouts/main.php';
?>