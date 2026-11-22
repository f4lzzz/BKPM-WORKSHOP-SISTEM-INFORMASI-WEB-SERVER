
<?php

class MahasiswaController
{
    private $mahasiswa;

    public function __construct($db)
    {
        $this->mahasiswa = new Mahasiswa($db);
    }

    // Menampilkan seluruh data mahasiswa
    // Admin dan user yang sudah login boleh melihat data
    public function index()
    {
        Auth::requireAuth();

        try {
            $data = $this->mahasiswa->getAll();

            Response::success("Data mahasiswa berhasil diambil", [
                "total" => count($data),
                "mahasiswa" => $data
            ]);
        } catch (PDOException $e) {
            Response::error(500, "Gagal mengambil data mahasiswa");
        }
    }

    // Menambahkan data mahasiswa khusus admin
    public function store()
    {
        Auth::requireAdmin();

        $data = json_decode(file_get_contents("php://input"), true);

        if (
            empty($data["nim"]) ||
            empty($data["nama"]) ||
            empty($data["prodi"])
        ) {
            Response::error(400, "NIM, nama, dan prodi wajib diisi");
        }

        try {
            $this->mahasiswa->create(
                $data["nim"],
                $data["nama"],
                $data["prodi"]
            );

            Response::json(201, true, "Data mahasiswa berhasil ditambahkan");
        } catch (PDOException $e) {
            Response::error(409, "NIM sudah terdaftar atau data tidak valid");
        }
    }

    // Mengubah data mahasiswa khusus admin
    public function update()
    {
        Auth::requireAdmin();

        $id = $_GET["id"] ?? null;
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$id || !is_numeric($id)) {
            Response::error(400, "ID mahasiswa tidak valid");
        }

        if (
            empty($data["nim"]) ||
            empty($data["nama"]) ||
            empty($data["prodi"])
        ) {
            Response::error(400, "NIM, nama, dan prodi wajib diisi");
        }

        try {
            $mahasiswa = $this->mahasiswa->getById($id);

            if (!$mahasiswa) {
                Response::error(404, "Data mahasiswa tidak ditemukan");
            }

            $this->mahasiswa->update(
                $id,
                $data["nim"],
                $data["nama"],
                $data["prodi"]
            );

            Response::success("Data mahasiswa berhasil diperbarui");
        } catch (PDOException $e) {
            Response::error(409, "NIM sudah digunakan atau data tidak valid");
        }
    }

    // Menghapus data mahasiswa khusus admin
    public function destroy()
    {
        Auth::requireAdmin();

        $id = $_GET["id"] ?? null;

        if (!$id || !is_numeric($id)) {
            Response::error(400, "ID mahasiswa tidak valid");
        }

        try {
            $mahasiswa = $this->mahasiswa->getById($id);

            if (!$mahasiswa) {
                Response::error(404, "Data mahasiswa tidak ditemukan");
            }

            $this->mahasiswa->delete($id);

            Response::success("Data mahasiswa berhasil dihapus");
        } catch (PDOException $e) {
            Response::error(500, "Gagal menghapus data mahasiswa");
        }
    }
}
