<?php

require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Models/MahasiswaRepository.php';
require_once __DIR__ . '/../Models/ProdiRepository.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';

class MahasiswaController extends BaseController
{
    private MahasiswaService $service;

    public function __construct()
    {
        $mahasiswaRepository = new MahasiswaRepository();
        $prodiRepository = new ProdiRepository();

        $this->service = new MahasiswaService(
            $mahasiswaRepository,
            $prodiRepository
        );
    }

    public function index(): void
    {
        $mahasiswa = $this->service->getAll();

        $this->view('mahasiswa/index', [
            'mahasiswa' => $mahasiswa
        ]);
    }

    public function create(): void
    {
        $prodi = $this->service->getProdi();

        $this->view('mahasiswa/create', [
            'prodi' => $prodi,
            'errors' => [],
            'old' => []
        ]);
    }

    public function store(): void
    {
        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? ''
        ];

        $result = $this->service->create($data);

        if (!$result['success']) {
            $prodi = $this->service->getProdi();

            $this->view('mahasiswa/create', [
                'prodi' => $prodi,
                'errors' => $result['errors'],
                'old' => $data
            ]);

            return;
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => $result['message']
        ];

        $this->redirect('/mahasiswa');
    }

    public function edit(int $id): void
    {
        $data = $this->service->getById($id);

        if (!$data) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data mahasiswa tidak ditemukan.'
            ];

            $this->redirect('/mahasiswa');
        }

        $prodi = $this->service->getProdi();

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $data,
            'prodi' => $prodi,
            'errors' => []
        ]);
    }

    public function update(int $id): void
    {
        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? ''
        ];

        $result = $this->service->update($id, $data);

        if (!$result['success']) {
            $mahasiswa = $this->service->getById($id);
            $prodi = $this->service->getProdi();

            $this->view('mahasiswa/edit', [
                'mahasiswa' => array_merge(
                    $mahasiswa ?? [],
                    $data
                ),
                'prodi' => $prodi,
                'errors' => $result['errors']
            ]);

            return;
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => $result['message']
        ];

        $this->redirect('/mahasiswa');
    }

    public function delete(int $id): void
    {
        $success = $this->service->delete($id);

        if ($success) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Data mahasiswa berhasil dihapus.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data mahasiswa gagal dihapus.'
            ];
        }

        $this->redirect('/mahasiswa');
    }
}