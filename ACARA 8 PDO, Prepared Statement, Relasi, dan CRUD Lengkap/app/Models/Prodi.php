
<?php

require_once __DIR__ . '/../Core/Database.php';

class Prodi
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    // Menampilkan semua prodi dan jumlah mahasiswa
    public function all(): array
    {
        return $this->pdo->query(
            "SELECT p.*, COUNT(m.id) AS jumlah_mahasiswa
             FROM prodi p
             LEFT JOIN mahasiswa m ON p.id = m.prodi_id
             GROUP BY p.id
             ORDER BY p.id ASC"
        )->fetchAll();
    }

    // Mencari prodi berdasarkan ID
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM prodi WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    // Menambah prodi
    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO prodi (nama, kode)
             VALUES (:nama, :kode)"
        );

        $stmt->execute($data);
    }

    // Mengubah prodi
    public function update(int $id, array $data): void
    {
        $data['id'] = $id;

        $stmt = $this->pdo->prepare(
            "UPDATE prodi
             SET nama = :nama, kode = :kode
             WHERE id = :id"
        );

        $stmt->execute($data);
    }

    // Menghapus prodi
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM prodi WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);
    }
}