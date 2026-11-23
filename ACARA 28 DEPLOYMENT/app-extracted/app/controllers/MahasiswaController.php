
<?php

class MahasiswaController
{
    private $mahasiswa;
    private $folderUpload;

    public function __construct($db)
    {
        $this->mahasiswa = new Mahasiswa($db);

        // Folder upload di public_html/uploads
        $this->folderUpload = dirname(__DIR__, 2) . "/public_html/uploads/";
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

    // Menghapus file foto
    private function hapusFoto($namaFoto)
    {
        if (empty($namaFoto)) {
            return;
        }

        $path = $this->folderUpload . basename($namaFoto);

        if (is_file($path)) {
            unlink($path);
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

        $ekstensi = strtolower(
            pathinfo($file["name"], PATHINFO_EXTENSION)
        );

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

        if (!is_dir($this->folderUpload)) {
            Response::error(500, "Folder uploads tidak ditemukan");
        }

        if (!is_writable($this->folderUpload)) {
            Response::error(500, "Folder uploads tidak memiliki izin tulis");
        }

        $namaBaru = uniqid("foto_", true) . "." . $ekstensi;
        $pathFoto = $this->folderUpload . $namaBaru;

        if (is_uploaded_file($file["tmp_name"])) {
            $fileTersimpan = move_uploaded_file(
                $file["tmp_name"],
                $pathFoto
            );
        } else {
            $fileTersimpan = rename(
                $file["tmp_name"],
                $pathFoto
            );
        }

        if (!$fileTersimpan) {
            Response::error(500, "Gagal menyimpan foto");
        }

        // Mengatur permission foto secara otomatis menjadi 0644
        if (!@chmod($pathFoto, 0644)) {
            @unlink($pathFoto);
            Response::error(500, "Gagal mengatur permission foto");
        }

        // Memastikan foto dapat dibaca oleh server
        if (!is_readable($pathFoto)) {
            @unlink($pathFoto);
            Response::error(500, "Foto tidak dapat dibaca oleh server");
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
            $this->hapusFoto($fotoBaru);

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

            if ($fotoBaru) {
                $this->hapusFoto($mahasiswa["foto"]);
            }

            Response::success("Data mahasiswa berhasil diperbarui");
        } catch (PDOException $e) {
            $this->hapusFoto($fotoBaru);

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

            $this->hapusFoto($mahasiswa["foto"]);

            Response::success("Data mahasiswa berhasil dihapus");
        } catch (PDOException $e) {
            Response::error(500, "Gagal menghapus data mahasiswa");
        }
    }
}
