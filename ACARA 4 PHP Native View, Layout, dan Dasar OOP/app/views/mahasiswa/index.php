<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold">Data Mahasiswa</h2>
        <p class="text-muted mb-0">
            Daftar mahasiswa
        </p>
    </div>

    <a href="app/views/mahasiswa/create.php"
       class="btn btn-primary">
        + Tambah Mahasiswa
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped
                          table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th>Angkatan</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($mahasiswa as $i => $mhs): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?= htmlspecialchars($mhs->getNim()) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($mhs->getNama()) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($mhs->getProdi()) ?>
                        </td>
                        <td>
                            <span class="badge bg-success">
                                <?= htmlspecialchars($mhs->getAngkatan()) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>