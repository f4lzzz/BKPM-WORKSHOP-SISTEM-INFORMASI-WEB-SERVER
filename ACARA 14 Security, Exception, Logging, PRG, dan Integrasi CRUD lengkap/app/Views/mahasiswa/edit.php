<?php

ob_start();

?>

<div class="row justify-content-center">

    <div class="col-md-8">

        <div class="card shadow-sm">

            <div class="card-header bg-warning">
                <h4 class="mb-0">Edit Mahasiswa</h4>
            </div>

            <div class="card-body">

                <form
                    action="<?= BASE_URL ?>/mahasiswa/update/<?= htmlspecialchars((string) $mahasiswa['id'], ENT_QUOTES, 'UTF-8') ?>"
                    method="POST"
                >

                    <div class="mb-3">

                        <label
                            for="nim"
                            class="form-label"
                        >
                            NIM
                        </label>

                        <input
                            type="text"
                            name="nim"
                            id="nim"
                            class="form-control"
                            maxlength="20"
                            value="<?= htmlspecialchars((string) $mahasiswa['nim'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >

                        <div class="form-text">
                            NIM harus berupa angka dan 8–20 karakter.
                        </div>

                    </div>

                    <div class="mb-3">

                        <label
                            for="nama"
                            class="form-label"
                        >
                            Nama
                        </label>

                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control"
                            maxlength="100"
                            value="<?= htmlspecialchars((string) $mahasiswa['nama'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            maxlength="100"
                            value="<?= htmlspecialchars((string) $mahasiswa['email'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label
                            for="angkatan"
                            class="form-label"
                        >
                            Angkatan
                        </label>

                        <input
                            type="number"
                            name="angkatan"
                            id="angkatan"
                            class="form-control"
                            min="2000"
                            max="2100"
                            value="<?= htmlspecialchars((string) $mahasiswa['angkatan'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >

                    </div>

                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-warning"
                        >
                            Update
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

    </div>

</div>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layouts/main.php';