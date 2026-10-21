
<?php

class Mahasiswa
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAll(): array
    {
        $sql = "SELECT id, nim, nama, prodi, created_at
                FROM mahasiswa
                ORDER BY id ASC";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $sql = "SELECT id, nim, nama, prodi, created_at
                FROM mahasiswa
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }
}
