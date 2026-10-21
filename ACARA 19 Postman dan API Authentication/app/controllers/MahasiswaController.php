
<?php

class MahasiswaController
{
    private Mahasiswa $mahasiswa;

    public function __construct()
    {
        $this->mahasiswa = new Mahasiswa();
    }

    public function index(): void
    {
        try {
            $data = $this->mahasiswa->getAll();

            Response::json([
                'success' => true,
                'message' => 'Data mahasiswa berhasil diambil',
                'data' => $data
            ], 200);
        } catch (PDOException $e) {
            Response::json([
                'success' => false,
                'message' => 'Gagal mengambil data mahasiswa'
            ], 500);
        }
    }
}
