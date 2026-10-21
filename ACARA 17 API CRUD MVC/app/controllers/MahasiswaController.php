
<?php

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    private function respond(
        array $body,
        int $status = 200
    ): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $body,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function readInput(): ?array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            $this->respond([
                'success' => false,
                'message' => 'Format JSON tidak valid'
            ], 400);

            return null;
        }

        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (
                !isset($data[$field]) ||
                !is_string($data[$field]) ||
                trim($data[$field]) === ''
            ) {
                $this->respond([
                    'success' => false,
                    'message' => "Field {$field} wajib diisi"
                ], 400);

                return null;
            }

            $data[$field] = trim($data[$field]);
        }

        return $data;
    }

    public function index(): void
    {
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (isset($_GET['id']) && (!$id || $id < 1)) {
            $this->respond([
                'success' => false,
                'message' => 'ID tidak valid'
            ], 400);

            return;
        }

        if ($id !== false && $id !== null) {
            $data = $this->model->find($id);

            if (!$data) {
                $this->respond([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);

                return;
            }

            $this->respond([
                'success' => true,
                'data' => $data
            ]);

            return;
        }

        $this->respond([
            'success' => true,
            'data' => $this->model->getAll()
        ]);
    }

    public function store(): void
    {
        $data = $this->readInput();

        if ($data === null) {
            return;
        }

        try {
            $id = $this->model->create($data);

            $this->respond([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => ['id' => $id]
            ], 201);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->respond([
                    'success' => false,
                    'message' => 'NIM sudah digunakan'
                ], 409);

                return;
            }

            throw $e;
        }
    }

    public function update(): void
    {
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$id || $id < 1) {
            $this->respond([
                'success' => false,
                'message' => 'ID wajib diisi dan harus valid'
            ], 400);

            return;
        }

        if (!$this->model->find($id)) {
            $this->respond([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);

            return;
        }

        $data = $this->readInput();

        if ($data === null) {
            return;
        }

        try {
            $this->model->update($id, $data);

            $this->respond([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $this->model->find($id)
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $this->respond([
                    'success' => false,
                    'message' => 'NIM sudah digunakan'
                ], 409);

                return;
            }

            throw $e;
        }
    }

    public function destroy(): void
    {
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$id || $id < 1) {
            $this->respond([
                'success' => false,
                'message' => 'ID wajib diisi dan harus valid'
            ], 400);

            return;
        }

        if (!$this->model->delete($id)) {
            $this->respond([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);

            return;
        }

        $this->respond([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
