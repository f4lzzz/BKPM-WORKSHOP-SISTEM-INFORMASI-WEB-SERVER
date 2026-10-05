
<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/Mahasiswa.php';

class MahasiswaRepository
{
    private PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    // READ: mengambil semua mahasiswa
    public function all(): array
    {
        $sql = "SELECT
                    mahasiswa.*,
                    prodi.nama AS nama_prodi
                FROM mahasiswa
                JOIN prodi
                    ON mahasiswa.prodi_id = prodi.id
                ORDER BY mahasiswa.id DESC";

        $stmt = $this->connection->query($sql);

        return $stmt->fetchAll();
    }

    // READ: mengambil satu mahasiswa berdasarkan ID
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM mahasiswa WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    // CREATE: menambahkan mahasiswa
    public function create(Mahasiswa $mahasiswa): bool
    {
        $sql = "INSERT INTO mahasiswa
                    (nim, nama, email, prodi_id, angkatan)
                VALUES
                    (:nim, :nama, :email, :prodi_id, :angkatan)";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'email' => $mahasiswa->getEmail(),
            'prodi_id' => $mahasiswa->getProdiId(),
            'angkatan' => $mahasiswa->getAngkatan()
        ]);
    }

    // UPDATE: memperbarui mahasiswa
    public function update(Mahasiswa $mahasiswa): bool
    {
        $sql = "UPDATE mahasiswa SET
                    nim = :nim,
                    nama = :nama,
                    email = :email,
                    prodi_id = :prodi_id,
                    angkatan = :angkatan
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'email' => $mahasiswa->getEmail(),
            'prodi_id' => $mahasiswa->getProdiId(),
            'angkatan' => $mahasiswa->getAngkatan(),
            'id' => $mahasiswa->getId()
        ]);
    }

    // DELETE: menghapus mahasiswa
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM mahasiswa WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    // Mengambil semua program studi untuk dropdown
    public function getProdi(): array
    {
        $sql = "SELECT id, nama FROM prodi ORDER BY nama ASC";

        $stmt = $this->connection->query($sql);

        return $stmt->fetchAll();
    }
}