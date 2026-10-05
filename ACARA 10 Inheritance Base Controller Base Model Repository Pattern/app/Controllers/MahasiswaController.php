
<?php

require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Models/MahasiswaRepository.php';

class MahasiswaController extends BaseController
{
    private MahasiswaRepository $repository;

    public function __construct(MahasiswaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): void
    {
        $mahasiswa = $this->repository->all();

        $this->view('mahasiswa/index', [
            'title' => 'Data Mahasiswa',
            'mahasiswa' => $mahasiswa
        ]);
    }

    public function create(): void
    {
        $prodi = $this->repository->getProdi();

        $this->view('mahasiswa/create', [
            'title' => 'Tambah Mahasiswa',
            'prodi' => $prodi,
            'data' => [],
            'error' => null
        ]);
    }

    public function store(): void
    {
        try {
            $mahasiswa = new Mahasiswa(
                trim($_POST['nim'] ?? ''),
                trim($_POST['nama'] ?? ''),
                trim($_POST['email'] ?? ''),
                (int) ($_POST['prodi_id'] ?? 0),
                (int) ($_POST['angkatan'] ?? 0)
            );

            $this->repository->create($mahasiswa);

            $this->redirect(BASE_URL . '/');
        } catch (Throwable $e) {
            $prodi = $this->repository->getProdi();

            $this->view('mahasiswa/create', [
                'title' => 'Tambah Mahasiswa',
                'prodi' => $prodi,
                'data' => $_POST,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $mahasiswa = $this->repository->find($id);

        if (!$mahasiswa) {
            http_response_code(404);
            echo 'Data mahasiswa tidak ditemukan.';
            return;
        }

        $prodi = $this->repository->getProdi();

        $this->view('mahasiswa/edit', [
            'title' => 'Edit Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'error' => null
        ]);
    }

    public function update(): void
    {
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

            $this->redirect(BASE_URL . '/');
        } catch (Throwable $e) {
            $id = (int) ($_POST['id'] ?? 0);
            $dataMahasiswa = $this->repository->find($id);
            $prodi = $this->repository->getProdi();

            $this->view('mahasiswa/edit', [
                'title' => 'Edit Mahasiswa',
                'mahasiswa' => $dataMahasiswa ?? $_POST,
                'prodi' => $prodi,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->repository->delete($id);
        }

        $this->redirect(BASE_URL . '/');
    }
}