
<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Tambah Mahasiswa</h4>
                </div>

                <div class="card-body p-4">
                    <form method="POST"
                          action="<?= BASE_URL ?>/mahasiswa/store">

                        <div class="mb-3">
                            <label for="nim" class="form-label">
                                NIM
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="nim"
                                   name="nim"
                                   required
                                   maxlength="20"
                                   placeholder="Masukkan NIM">
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Lengkap
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="nama"
                                   name="nama"
                                   required
                                   placeholder="Masukkan nama mahasiswa">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   placeholder="nama@email.com">
                        </div>

                        <div class="mb-3">
                            <label for="prodi_id" class="form-label">
                                Program Studi
                            </label>
                            <select class="form-select"
                                    id="prodi_id"
                                    name="prodi_id"
                                    required>
                                <option value="">Pilih Program Studi</option>

                                <?php foreach ($prodi as $p): ?>
                                    <option value="<?= e($p['id']) ?>">
                                        <?= e($p['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="angkatan" class="form-label">
                                Angkatan
                            </label>
                            <input type="number"
                                   class="form-control"
                                   id="angkatan"
                                   name="angkatan"
                                   min="2000"
                                   max="2100"
                                   value="<?= date('Y') ?>"
                                   required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>/mahasiswa"
                               class="btn btn-secondary">
                                Kembali
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                Simpan Mahasiswa
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>