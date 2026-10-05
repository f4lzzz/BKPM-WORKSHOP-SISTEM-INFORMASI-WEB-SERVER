
<?php
require_once __DIR__ . '/../Models/Mahasiswa.php';
require_once __DIR__ . '/../Models/Prodi.php';

class MahasiswaController
{
    private Mahasiswa $mahasiswaModel;
    private Prodi $prodiModel;

    public function __construct()
    {
        $this->mahasiswaModel = new Mahasiswa();
        $this->prodiModel = new Prodi();
    }

    private function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    // Menampilkan daftar mahasiswa
    public function index(): void
    {
        $keyword = trim($_GET['search'] ?? '');
        $mahasiswa = $this->mahasiswaModel->all($keyword);

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }

    // Form tambah
    public function create(): void
    {
        $prodi = $this->prodiModel->all();

        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);

        require __DIR__ . '/../Views/mahasiswa/create.php';
    }

    // Simpan data
    public function store(): void
    {
        $data = [
            'nim' => trim($_POST['nim'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? 0)
        ];

        if (
            $data['nim'] === '' ||
            $data['nama'] === '' ||
            $data['prodi_id'] <= 0 ||
            $data['angkatan'] < 2000
        ) {
            $_SESSION['error'] = 'Data belum lengkap atau tidak valid.';
            $this->redirect('/mahasiswa/create');
        }

        try {
            $this->mahasiswaModel->create($data);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $_SESSION['error'] = 'NIM sudah terdaftar. Gunakan NIM lain.';
                $this->redirect('/mahasiswa/create');
            }

            throw $e;
        }

        $this->redirect('/mahasiswa');
    }

    // Form edit
    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $mahasiswa = $this->mahasiswaModel->find($id);
        $prodi = $this->prodiModel->all();

        if (!$mahasiswa) {
            http_response_code(404);
            die('Data mahasiswa tidak ditemukan.');
        }

        require __DIR__ . '/../Views/mahasiswa/edit.php';
    }

    // Update data
    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        $data = [
            'nim' => trim($_POST['nim'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? 0)
        ];

        if (
            $id <= 0 ||
            $data['nim'] === '' ||
            $data['nama'] === '' ||
            $data['prodi_id'] <= 0 ||
            $data['angkatan'] < 2000
        ) {
            die('Data mahasiswa belum lengkap atau tidak valid.');
        }

        try {
            $this->mahasiswaModel->update($id, $data);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                die('NIM sudah digunakan mahasiswa lain.');
            }

            throw $e;
        }

        $this->redirect('/mahasiswa');
    }

    // Hapus data
    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            die('ID mahasiswa tidak valid.');
        }

        $this->mahasiswaModel->delete($id);

        $this->redirect('/mahasiswa');
    }
}