<?php

class MahasiswaController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new Mahasiswa($db);
    }

    public function index()
    {
        $data = $this->model->getAll();

        Response::success("Data mahasiswa berhasil diambil", $data);
    }

    public function store()
    {
        Auth::validateToken();

        $data = json_decode(file_get_contents("php://input"), true) ?? [];

        $nim = trim($data["nim"] ?? "");
        $nama = trim($data["nama"] ?? "");
        $prodi = trim($data["prodi"] ?? "");

        if ($nim === "" || $nama === "" || $prodi === "") {
            Response::error("NIM, nama, dan prodi wajib diisi", 400);
        }

        try {
            $this->model->create($nim, $nama, $prodi);
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                Response::error("NIM sudah terdaftar", 409);
            }

            Response::error("Gagal menambahkan data mahasiswa", 500);
        }

        Response::success("Data mahasiswa berhasil ditambahkan", null, 201);
    }

    public function update($id)
    {
        Auth::validateToken();

        $mahasiswa = $this->model->getById($id);

        if (!$mahasiswa) {
            Response::error("Data mahasiswa tidak ditemukan", 404);
        }

        $data = json_decode(file_get_contents("php://input"), true) ?? [];

        $nim = trim($data["nim"] ?? "");
        $nama = trim($data["nama"] ?? "");
        $prodi = trim($data["prodi"] ?? "");

        if ($nim === "" || $nama === "" || $prodi === "") {
            Response::error("NIM, nama, dan prodi wajib diisi", 400);
        }

        try {
            $this->model->update($id, $nim, $nama, $prodi);
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                Response::error("NIM sudah digunakan mahasiswa lain", 409);
            }

            Response::error("Gagal memperbarui data mahasiswa", 500);
        }

        Response::success("Data mahasiswa berhasil diperbarui");
    }

    public function destroy($id)
    {
        Auth::validateToken();

        $mahasiswa = $this->model->getById($id);

        if (!$mahasiswa) {
            Response::error("Data mahasiswa tidak ditemukan", 404);
        }

        $this->model->delete($id);

        Response::success("Data mahasiswa berhasil dihapus");
    }
}
