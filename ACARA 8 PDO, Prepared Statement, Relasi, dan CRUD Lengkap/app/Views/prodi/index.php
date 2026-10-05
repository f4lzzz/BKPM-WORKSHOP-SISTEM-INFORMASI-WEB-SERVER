<?php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<h2>Data Program Studi</h2>

<p>
    <a href="<?= BASE_URL ?>/prodi/create">+ Tambah Prodi</a>
</p>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Program Studi</th>
            <th>Jumlah Mahasiswa</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($prodi)): ?>
            <tr>
                <td colspan="5">Belum ada data program studi.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($prodi as $index => $p): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= e($p['kode']) ?></td>
                    <td><?= e($p['nama']) ?></td>
                    <td><?= (int) ($p['jumlah_mahasiswa'] ?? 0) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/prodi/edit?id=<?= (int) $p['id'] ?>">Edit</a>

                        <form
                            action="<?= BASE_URL ?>/prodi/delete"
                            method="POST"
                            style="display:inline"
                            onsubmit="return confirm('Yakin ingin menghapus prodi ini?')"
                        >
                            <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>