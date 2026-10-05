<?php
require_once __DIR__ . '/../Models/Matakuliah.php';

class MatakuliahController
{
    private Matakuliah $matakuliah;

    public function __construct()
    {
        $this->matakuliah = new Matakuliah();
    }

    // Menampilkan daftar mata kuliah
    public function index(): void
    {
        $matakuliah = $this->matakuliah->all();

        require __DIR__ . '/../Views/matakuliah/index.php';
    }

    // Menampilkan form tambah mata kuliah
    public function create(): void
    {
        require __DIR__ . '/../Views/matakuliah/create.php';
    }

    // Menyimpan mata kuliah baru
    public function store(): void
    {
        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'sks' => (int) ($_POST['sks'] ?? 0)
        ];

        if (
            $data['kode'] === '' ||
            $data['nama'] === '' ||
            $data['sks'] < 1 ||
            $data['sks'] > 6
        ) {
            die('Data mata kuliah belum lengkap atau tidak valid.');
        }

        $this->matakuliah->create($data);

        header('Location: /matakuliah');
        exit;
    }

    // Menampilkan form edit mata kuliah
    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $matakuliah = $this->matakuliah->find($id);

        if (!$matakuliah) {
            http_response_code(404);
            die('Data mata kuliah tidak ditemukan.');
        }

        require __DIR__ . '/../Views/matakuliah/edit.php';
    }

    // Memperbarui mata kuliah
    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'sks' => (int) ($_POST['sks'] ?? 0)
        ];

        if (
            $id <= 0 ||
            $data['kode'] === '' ||
            $data['nama'] === '' ||
            $data['sks'] < 1 ||
            $data['sks'] > 6
        ) {
            die('Data mata kuliah belum lengkap atau tidak valid.');
        }

        $this->matakuliah->update($id, $data);

        header('Location: /matakuliah');
        exit;
    }

    // Menghapus mata kuliah
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Metode tidak diizinkan.');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            die('ID mata kuliah tidak valid.');
        }

        $this->matakuliah->delete($id);

        header('Location: /matakuliah');
        exit;
    }
}