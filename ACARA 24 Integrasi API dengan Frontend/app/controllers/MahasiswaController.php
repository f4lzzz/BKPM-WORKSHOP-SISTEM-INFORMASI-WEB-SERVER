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
        try {
            $stmt = $this->model->getAll();
            $mahasiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Response::success(
                "Data mahasiswa berhasil diambil",
                $mahasiswa
            );
        } catch (PDOException $exception) {
            Response::error("Gagal mengambil data mahasiswa", 500);
        }
    }
}
