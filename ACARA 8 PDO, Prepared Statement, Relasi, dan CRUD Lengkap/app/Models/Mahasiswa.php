
<?php

require_once __DIR__ . '/../Core/Database.php';

class Mahasiswa
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    // Menampilkan semua mahasiswa dan pencarian nama/NIM
    public function all(string $search = ''): array
    {
        $sql = "SELECT m.*, p.nama AS prodi_nama
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                WHERE m.nama LIKE :nama
                   OR m.nim LIKE :nim
                ORDER BY m.nim ASC";

        $stmt = $this->pdo->prepare($sql);
        $keyword = '%' . $search . '%';

        $stmt->execute([
            'nama' => $keyword,
            'nim' => $keyword
        ]);

        return $stmt->fetchAll();
    }

    // Mencari mahasiswa berdasarkan ID
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM mahasiswa WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    // Menambah mahasiswa
    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO mahasiswa
            (nim, nama, email, prodi_id, angkatan)
            VALUES
            (:nim, :nama, :email, :prodi_id, :angkatan)"
        );

        $stmt->execute($data);
    }

    // Mengubah mahasiswa
    public function update(int $id, array $data): void
    {
        $data['id'] = $id;

        $stmt = $this->pdo->prepare(
            "UPDATE mahasiswa SET
                nim = :nim,
                nama = :nama,
                email = :email,
                prodi_id = :prodi_id,
                angkatan = :angkatan
            WHERE id = :id"
        );

        $stmt->execute($data);
    }

    // Menghapus mahasiswa
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM mahasiswa WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);
    }
}