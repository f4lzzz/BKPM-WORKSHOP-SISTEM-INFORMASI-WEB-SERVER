
<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';
require_once __DIR__ . '/../Models/MahasiswaRepository.php';

class MahasiswaController
{
    private MahasiswaRepository $repository;

    public function __construct(MahasiswaRepository $repository)
    {
        $this->repository = $repository;
    }

    // Menampilkan seluruh data mahasiswa
    public function index(): void
    {
        $mahasiswa = $this->repository->all();

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }

    // Menampilkan form tambah mahasiswa
    public function create(): void
    {
        $error = null;
        $prodi = $this->repository->getProdi();

        $data = [
            'nim' => '',
            'nama' => '',
            'email' => '',
            'prodi_id' => '',
            'angkatan' => ''
        ];

        require __DIR__ . '/../Views/mahasiswa/create.php';
    }

    // Menyimpan mahasiswa baru
    public function store(): void
    {
        $data = $_POST;
        $prodi = $this->repository->getProdi();

        try {
            $mahasiswa = new Mahasiswa(
                trim($_POST['nim'] ?? ''),
                trim($_POST['nama'] ?? ''),
                trim($_POST['email'] ?? ''),
                (int) ($_POST['prodi_id'] ?? 0),
                (int) ($_POST['angkatan'] ?? 0)
            );

            $this->repository->create($mahasiswa);

            header('Location: ' . BASE_URL . '/');
            exit;
        } catch (Throwable $e) {
            $error = $e->getMessage();

            require __DIR__ . '/../Views/mahasiswa/create.php';
        }
    }

    // Menampilkan form edit mahasiswa
    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $data = $this->repository->find($id);

        if (!$data) {
            http_response_code(404);
            echo 'Data mahasiswa tidak ditemukan.';
            return;
        }

        $error = null;
        $prodi = $this->repository->getProdi();

        require __DIR__ . '/../Views/mahasiswa/edit.php';
    }

    // Memperbarui data mahasiswa
    public function update(): void
    {
        $data = $_POST;
        $prodi = $this->repository->getProdi();

        try {
            $id = (int) ($_POST['id'] ?? 0);

            $mahasiswa = new Mahasiswa(
                trim($_POST['nim'] ?? ''),
                trim($_POST['nama'] ?? ''),
                trim($_POST['email'] ?? ''),
                (int) ($_POST['prodi_id'] ?? 0),
                (int) ($_POST['angkatan'] ?? 0),
                $id
            );

            $this->repository->update($mahasiswa);

            header('Location: ' . BASE_URL . '/');
            exit;
        } catch (Throwable $e) {
            $error = $e->getMessage();

            require __DIR__ . '/../Views/mahasiswa/edit.php';
        }
    }

    // Menghapus mahasiswa
    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->repository->delete($id);
        }

        header('Location: ' . BASE_URL . '/');
        exit;
    }
}