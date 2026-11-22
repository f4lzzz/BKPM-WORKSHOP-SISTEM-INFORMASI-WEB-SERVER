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

        Response::success(
            'Data mahasiswa berhasil diambil',
            $data
        );
    }

    public function store()
    {
        Auth::validateToken();

        $data = json_decode(file_get_contents("php://input"), true);

        if (
            empty($data['nim']) ||
            empty($data['nama']) ||
            empty($data['prodi'])
        ) {
            Response::error('NIM, nama, dan prodi wajib diisi', 400);
        }

        try {
            $this->model->create($data);

            Response::success(
                'Data mahasiswa berhasil ditambahkan',
                $data,
                201
            );
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                Response::error('NIM sudah digunakan', 409);
            }

            Response::error('Gagal menambahkan data mahasiswa', 500);
        }
    }

    public function update($id)
    {
        Auth::validateToken();

        $data = json_decode(file_get_contents("php://input"), true);

        if (
            empty($data['nim']) ||
            empty($data['nama']) ||
            empty($data['prodi'])
        ) {
            Response::error('NIM, nama, dan prodi wajib diisi', 400);
        }

        if (!$this->model->getById($id)) {
            Response::error('Data mahasiswa tidak ditemukan', 404);
        }

        try {
            $this->model->update($id, $data);

            Response::success(
                'Data mahasiswa berhasil diperbarui',
                $this->model->getById($id)
            );
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                Response::error('NIM sudah digunakan', 409);
            }

            Response::error('Gagal memperbarui data mahasiswa', 500);
        }
    }

    public function destroy($id)
    {
        Auth::validateToken();

        if (!$this->model->getById($id)) {
            Response::error('Data mahasiswa tidak ditemukan', 404);
        }

        $this->model->delete($id);

        Response::success('Data mahasiswa berhasil dihapus');
    }
}
