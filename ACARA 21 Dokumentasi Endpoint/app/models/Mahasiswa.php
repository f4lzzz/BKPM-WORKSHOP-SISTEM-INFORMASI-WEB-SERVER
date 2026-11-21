<?php

require_once __DIR__ . '/../config/Database.php';

class Mahasiswa
{
    private PDO $conn;
    private string $table = 'mahasiswa';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();

        if ($this->conn === null) {
            throw new RuntimeException('Koneksi database gagal');
        }
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM {$this->table} ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(string $nim, string $nama, string $prodi): string
    {
        $query = "INSERT INTO {$this->table} (nim, nama, prodi)
                  VALUES (:nim, :nama, :prodi)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':nim' => $nim,
            ':nama' => $nama,
            ':prodi' => $prodi
        ]);

        return $this->conn->lastInsertId();
    }

    public function update(
        int $id,
        string $nim,
        string $nama,
        string $prodi
    ): bool {
        $query = "UPDATE {$this->table}
                  SET nim = :nim, nama = :nama, prodi = :prodi
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $id,
            ':nim' => $nim,
            ':nama' => $nama,
            ':prodi' => $prodi
        ]);
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
