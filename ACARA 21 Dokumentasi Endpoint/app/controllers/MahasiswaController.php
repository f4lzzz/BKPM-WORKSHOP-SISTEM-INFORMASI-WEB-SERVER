<?php

require_once __DIR__ . '/../models/Mahasiswa.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Response.php';

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    public function index(): void
    {
        try {
            $data = $this->model->getAll();

            Response::json(
                200,
                true,
                'Data mahasiswa berhasil diambil',
                $data
            );
        } catch (Throwable $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function store(): void
    {
        Auth::requireAuth();

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!is_array($input)) {
                Response::json(400, false, 'Format JSON tidak valid');
            }

            $nim = trim($input['nim'] ?? '');
            $nama = trim($input['nama'] ?? '');
            $prodi = trim($input['prodi'] ?? '');

            if ($nim === '' || $nama === '' || $prodi === '') {
                Response::json(400, false, 'NIM, nama, dan prodi wajib diisi');
            }

            $id = $this->model->create($nim, $nama, $prodi);

            Response::json(201, true, 'Data mahasiswa berhasil ditambahkan', [
                'id' => $id
            ]);
        } catch (Throwable $e) {
            error_log($e->getMessage());

            if ($e instanceof PDOException && $e->getCode() === '23000') {
                Response::json(409, false, 'NIM sudah terdaftar');
            }

            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function update(int $id): void
    {
        Auth::requireAuth();

        try {
            if ($id <= 0) {
                Response::json(400, false, 'ID mahasiswa tidak valid');
            }

            $mahasiswa = $this->model->findById($id);

            if (!$mahasiswa) {
                Response::json(404, false, 'Data mahasiswa tidak ditemukan');
            }

            $input = json_decode(file_get_contents('php://input'), true);

            if (!is_array($input)) {
                Response::json(400, false, 'Format JSON tidak valid');
            }

            $nim = trim($input['nim'] ?? '');
            $nama = trim($input['nama'] ?? '');
            $prodi = trim($input['prodi'] ?? '');

            if ($nim === '' || $nama === '' || $prodi === '') {
                Response::json(400, false, 'NIM, nama, dan prodi wajib diisi');
            }

            $this->model->update($id, $nim, $nama, $prodi);

            Response::json(200, true, 'Data mahasiswa berhasil diubah');
        } catch (Throwable $e) {
            error_log($e->getMessage());

            if ($e instanceof PDOException && $e->getCode() === '23000') {
                Response::json(409, false, 'NIM sudah digunakan mahasiswa lain');
            }

            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function destroy(int $id): void
    {
        Auth::requireAuth();

        try {
            if ($id <= 0) {
                Response::json(400, false, 'ID mahasiswa tidak valid');
            }

            $mahasiswa = $this->model->findById($id);

            if (!$mahasiswa) {
                Response::json(404, false, 'Data mahasiswa tidak ditemukan');
            }

            $this->model->delete($id);

            Response::json(200, true, 'Data mahasiswa berhasil dihapus');
        } catch (Throwable $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }
}
