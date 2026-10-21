
<?php

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    /**
     * Membaca dan memvalidasi input JSON.
     */
    private function readInput(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            Response::json(
                400,
                false,
                'Format JSON tidak valid'
            );
        }

        $errors = [];

        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (
                !isset($data[$field]) ||
                !is_string($data[$field]) ||
                trim($data[$field]) === ''
            ) {
                $errors[$field] = "Field {$field} wajib diisi";
            } else {
                $data[$field] = trim($data[$field]);
            }
        }

        if (!empty($errors)) {
            Response::json(
                400,
                false,
                'Validasi field gagal',
                null,
                $errors
            );
        }

        // NIM harus tepat 9 digit angka.
        if (!preg_match('/^[0-9]{9}$/', $data['nim'])) {
            Response::json(
                422,
                false,
                'Validasi gagal',
                null,
                [
                    'nim' => 'NIM harus terdiri dari tepat 9 digit angka'
                ]
            );
        }

        return $data;
    }

    /**
     * GET /mahasiswa
     * GET /mahasiswa?id=1
     */
    public function index(): void
    {
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (
            isset($_GET['id']) &&
            ($id === false || $id === null || $id < 1)
        ) {
            Response::json(
                400,
                false,
                'ID tidak valid'
            );
        }

        if ($id !== false && $id !== null) {
            $data = $this->model->find($id);

            if ($data === false) {
                Response::json(
                    404,
                    false,
                    'Data mahasiswa tidak ditemukan'
                );
            }

            Response::json(
                200,
                true,
                'Data mahasiswa berhasil ditemukan',
                $data
            );
        }

        Response::json(
            200,
            true,
            'Data mahasiswa berhasil diambil',
            $this->model->getAll()
        );
    }

    /**
     * POST /mahasiswa
     */
    public function store(): void
    {
        $data = $this->readInput();

        try {
            $id = $this->model->create($data);

            Response::json(
                201,
                true,
                'Data mahasiswa berhasil ditambahkan',
                [
                    'id' => (int) $id,
                    'nim' => $data['nim'],
                    'nama' => $data['nama'],
                    'prodi' => $data['prodi']
                ]
            );
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                Response::json(
                    409,
                    false,
                    'NIM sudah digunakan',
                    null,
                    [
                        'nim' => 'Gunakan NIM yang berbeda'
                    ]
                );
            }

            throw $e;
        }
    }

    /**
     * PUT /mahasiswa?id=1
     */
    public function update(): void
    {
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id === null || $id < 1) {
            Response::json(
                400,
                false,
                'ID wajib diisi dan harus berupa bilangan bulat positif'
            );
        }

        if ($this->model->find($id) === false) {
            Response::json(
                404,
                false,
                'Data mahasiswa tidak ditemukan'
            );
        }

        $data = $this->readInput();

        try {
            $this->model->update($id, $data);

            Response::json(
                200,
                true,
                'Data mahasiswa berhasil diperbarui',
                $this->model->find($id)
            );
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                Response::json(
                    409,
                    false,
                    'NIM sudah digunakan',
                    null,
                    [
                        'nim' => 'Gunakan NIM yang berbeda'
                    ]
                );
            }

            throw $e;
        }
    }

    /**
     * DELETE /mahasiswa?id=1
     */
    public function destroy(): void
    {
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id === null || $id < 1) {
            Response::json(
                400,
                false,
                'ID wajib diisi dan harus berupa bilangan bulat positif'
            );
        }

        if ($this->model->find($id) === false) {
            Response::json(
                404,
                false,
                'Data mahasiswa tidak ditemukan'
            );
        }

        $this->model->delete($id);

        Response::json(
            200,
            true,
            'Data mahasiswa berhasil dihapus',
            [
                'id' => $id
            ]
        );
    }
}
