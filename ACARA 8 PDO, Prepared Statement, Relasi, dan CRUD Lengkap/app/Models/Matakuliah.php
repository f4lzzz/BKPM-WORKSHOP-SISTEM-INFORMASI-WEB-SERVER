
<?php

require_once __DIR__ . '/../Core/Database.php';

class Matakuliah
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    // Menampilkan semua mata kuliah
    public function all(): array
    {
        return $this->pdo->query(
            "SELECT * FROM matakuliah ORDER BY kode ASC"
        )->fetchAll();
    }

    // Mencari mata kuliah berdasarkan ID
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM matakuliah WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    // Menambah mata kuliah
    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO matakuliah (kode, nama, sks)
             VALUES (:kode, :nama, :sks)"
        );

        $stmt->execute($data);
    }

    // Mengubah mata kuliah
    public function update(int $id, array $data): void
    {
        $data['id'] = $id;

        $stmt = $this->pdo->prepare(
            "UPDATE matakuliah
             SET kode = :kode, nama = :nama, sks = :sks
             WHERE id = :id"
        );

        $stmt->execute($data);
    }

    // Menghapus mata kuliah
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM matakuliah WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);
    }
}