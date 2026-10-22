
<?php

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
            error_log('Gagal mengambil data mahasiswa: ' . $e->getMessage());

            Response::json(
                500,
                false,
                'Terjadi kesalahan pada server'
            );
        }
    }

    public function store(): void
    {
        Auth::requireAuth();

        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!is_array($data)) {
                Response::json(400, false, 'Format JSON tidak valid');
            }

            $nim = trim($data['nim'] ?? '');
            $nama = trim($data['nama'] ?? '');
            $prodi = trim($data['prodi'] ?? '');

            if ($nim === '' || $nama === '' || $prodi === '') {
                Response::json(400, false, 'NIM, nama, dan prodi wajib diisi');
            }

            $id = $this->model->create([
                'nim' => $nim,
                'nama' => $nama,
                'prodi' => $prodi
            ]);

            Response::json(
                201,
                true,
                'Data mahasiswa berhasil ditambahkan',
                ['id' => $id]
            );
        } catch (Throwable $e) {
            error_log('Gagal menambahkan mahasiswa: ' . $e->getMessage());

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

            if (!$this->model->findById($id)) {
                Response::json(404, false, 'Data mahasiswa tidak ditemukan');
            }

            $data = json_decode(file_get_contents('php://input'), true);

            if (!is_array($data)) {
                Response::json(400, false, 'Format JSON tidak valid');
            }

            $nim = trim($data['nim'] ?? '');
            $nama = trim($data['nama'] ?? '');
            $prodi = trim($data['prodi'] ?? '');

            if ($nim === '' || $nama === '' || $prodi === '') {
                Response::json(400, false, 'NIM, nama, dan prodi wajib diisi');
            }

            $this->model->update($id, [
                'nim' => $nim,
                'nama' => $nama,
                'prodi' => $prodi
            ]);

            Response::json(200, true, 'Data mahasiswa berhasil diubah');
        } catch (Throwable $e) {
            error_log('Gagal mengubah data mahasiswa: ' . $e->getMessage());

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

            if (!$this->model->findById($id)) {
                Response::json(404, false, 'Data mahasiswa tidak ditemukan');
            }

            $this->model->delete($id);

            Response::json(200, true, 'Data mahasiswa berhasil dihapus');
        } catch (Throwable $e) {
            error_log('Gagal menghapus data mahasiswa: ' . $e->getMessage());

            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }
}
