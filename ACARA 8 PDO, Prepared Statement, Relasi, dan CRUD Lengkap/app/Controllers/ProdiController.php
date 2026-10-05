<?php
require_once __DIR__ . '/../Models/Prodi.php';

class ProdiController
{
    private Prodi $prodi;

    public function __construct()
    {
        $this->prodi = new Prodi();
    }

    // Menampilkan daftar prodi
    public function index(): void
    {
        $prodi = $this->prodi->all();

        require __DIR__ . '/../Views/prodi/index.php';
    }

    // Menampilkan form tambah prodi
    public function create(): void
    {
        require __DIR__ . '/../Views/prodi/create.php';
    }

    // Menyimpan prodi baru
    public function store(): void
    {
        $data = [
            'nama' => trim($_POST['nama'] ?? ''),
            'kode' => trim($_POST['kode'] ?? '')
        ];

        if ($data['nama'] === '' || $data['kode'] === '') {
            die('Nama dan kode prodi wajib diisi.');
        }

        $this->prodi->create($data);

        header('Location: /prodi');
        exit;
    }

    // Menampilkan form edit prodi
    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $prodi = $this->prodi->find($id);

        if (!$prodi) {
            http_response_code(404);
            die('Data prodi tidak ditemukan.');
        }

        require __DIR__ . '/../Views/prodi/edit.php';
    }

    // Memperbarui data prodi
    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        $data = [
            'nama' => trim($_POST['nama'] ?? ''),
            'kode' => trim($_POST['kode'] ?? '')
        ];

        if ($id <= 0 || $data['nama'] === '' || $data['kode'] === '') {
            die('Data prodi belum lengkap atau tidak valid.');
        }

        $this->prodi->update($id, $data);

        header('Location: /prodi');
        exit;
    }

    // Menghapus data prodi
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Metode tidak diizinkan.');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            die('ID prodi tidak valid.');
        }

        $this->prodi->delete($id);

        header('Location: /prodi');
        exit;
    }
}