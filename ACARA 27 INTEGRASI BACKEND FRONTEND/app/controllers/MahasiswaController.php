
<?php

class MahasiswaController
{
    private $mahasiswa;

    public function __construct($db)
    {
        $this->mahasiswa = new Mahasiswa($db);
    }

    // Menampilkan seluruh data atau satu data mahasiswa
    public function index()
    {
        Auth::requireAuth();

        try {
            $id = $_GET["id"] ?? null;

            if ($id !== null) {
                if (!is_numeric($id)) {
                    Response::error(400, "ID mahasiswa tidak valid");
                }

                $data = $this->mahasiswa->getById((int) $id);

                if (!$data) {
                    Response::error(404, "Data mahasiswa tidak ditemukan");
                }

                Response::success("Data mahasiswa berhasil diambil", $data);
            }

            $data = $this->mahasiswa->getAll();

            Response::success("Data mahasiswa berhasil diambil", [
                "total" => count($data),
                "mahasiswa" => $data
            ]);
        } catch (PDOException $e) {
            Response::error(500, "Gagal mengambil data mahasiswa");
        }
    }

    // Upload dan validasi foto mahasiswa
    private function uploadFoto()
    {
        if (
            !isset($_FILES["foto"]) ||
            $_FILES["foto"]["error"] === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        $file = $_FILES["foto"];

        if ($file["error"] !== UPLOAD_ERR_OK) {
            Response::error(422, "Upload foto gagal");
        }

        if ($file["size"] > 2 * 1024 * 1024) {
            Response::error(422, "Ukuran foto maksimal 2MB");
        }

        $ekstensi = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        if (!in_array($ekstensi, ["jpg", "jpeg", "png"])) {
            Response::error(422, "Foto harus berformat JPG atau PNG");
        }

        if (!function_exists("exif_imagetype")) {
            Response::error(500, "Ekstensi PHP EXIF belum tersedia");
        }

        $tipeGambar = @exif_imagetype($file["tmp_name"]);

        $tipeValid = [
            "jpg" => IMAGETYPE_JPEG,
            "jpeg" => IMAGETYPE_JPEG,
            "png" => IMAGETYPE_PNG
        ];

        if (
            $tipeGambar === false ||
            $tipeGambar !== $tipeValid[$ekstensi]
        ) {
            Response::error(422, "Berkas bukan gambar JPG/PNG yang valid");
        }

        $folderUpload = __DIR__ . "/../../public/uploads/";

        if (!is_dir($folderUpload)) {
            Response::error(500, "Folder uploads tidak ditemukan");
        }

        $namaBaru = uniqid("foto_", true) . "." . $ekstensi;
        $fileTersimpan = false;

        if (is_uploaded_file($file["tmp_name"])) {
            $fileTersimpan = move_uploaded_file(
                $file["tmp_name"],
                $folderUpload . $namaBaru
            );
        } else {
            $fileTersimpan = rename(
                $file["tmp_name"],
                $folderUpload . $namaBaru
            );
        }

        if (!$fileTersimpan) {
            Response::error(500, "Gagal menyimpan foto");
        }

        return $namaBaru;
    }

    // Menambahkan data mahasiswa khusus admin
    public function store()
    {
        Auth::requireAdmin();

        $data = $_POST;

        if (
            empty($data["nim"]) ||
            empty($data["nama"]) ||
            empty($data["prodi"])
        ) {
            Response::error(400, "NIM, nama, dan prodi wajib diisi");
        }

        $fotoBaru = $this->uploadFoto();
        $data["foto"] = $fotoBaru;

        try {
            $this->mahasiswa->create($data);

            Response::json(201, true, "Data mahasiswa berhasil ditambahkan");
        } catch (PDOException $e) {
            if ($fotoBaru) {
                $path = __DIR__ . "/../../public/uploads/" . $fotoBaru;

                if (file_exists($path)) {
                    unlink($path);
                }
            }

            Response::error(409, "NIM sudah terdaftar atau data tidak valid");
        }
    }

    // Mengubah data mahasiswa khusus admin
    public function update()
    {
        Auth::requireAdmin();

        $id = $_GET["id"] ?? null;

        if (!$id || !is_numeric($id)) {
            Response::error(400, "ID mahasiswa tidak valid");
        }

        $mahasiswa = $this->mahasiswa->getById((int) $id);

        if (!$mahasiswa) {
            Response::error(404, "Data mahasiswa tidak ditemukan");
        }

        $data = $_POST;

        if (
            empty($data["nim"]) ||
            empty($data["nama"]) ||
            empty($data["prodi"])
        ) {
            Response::error(400, "NIM, nama, dan prodi wajib diisi");
        }

        $fotoBaru = $this->uploadFoto();
        $data["foto"] = $fotoBaru ?? $mahasiswa["foto"];

        try {
            $this->mahasiswa->update((int) $id, $data);

            if ($fotoBaru && !empty($mahasiswa["foto"])) {
                $fotoLama = __DIR__ . "/../../public/uploads/" .
                    basename($mahasiswa["foto"]);

                if (file_exists($fotoLama)) {
                    unlink($fotoLama);
                }
            }

            Response::success("Data mahasiswa berhasil diperbarui");
        } catch (PDOException $e) {
            if ($fotoBaru) {
                $path = __DIR__ . "/../../public/uploads/" . $fotoBaru;

                if (file_exists($path)) {
                    unlink($path);
                }
            }

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
            $mahasiswa = $this->mahasiswa->getById((int) $id);

            if (!$mahasiswa) {
                Response::error(404, "Data mahasiswa tidak ditemukan");
            }

            $this->mahasiswa->delete((int) $id);

            if (!empty($mahasiswa["foto"])) {
                $fotoPath = __DIR__ . "/../../public/uploads/" .
                    basename($mahasiswa["foto"]);

                if (file_exists($fotoPath)) {
                    unlink($fotoPath);
                }
            }

            Response::success("Data mahasiswa berhasil dihapus");
        } catch (PDOException $e) {
            Response::error(500, "Gagal menghapus data mahasiswa");
        }
    }
}