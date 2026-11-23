
<?php

class Mahasiswa
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM mahasiswa ORDER BY id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM mahasiswa WHERE id = :id"
        );

        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ?: null;
    }

    public function create(array $data): string
    {
        $stmt = $this->db->prepare(
            "INSERT INTO mahasiswa (nim, nama, prodi, foto)
             VALUES (:nim, :nama, :prodi, :foto)"
        );

        $stmt->execute([
            ':nim' => $data['nim'],
            ':nama' => $data['nama'],
            ':prodi' => $data['prodi'],
            ':foto' => $data['foto'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE mahasiswa
             SET nim = :nim, nama = :nama, prodi = :prodi,
                 foto = :foto
             WHERE id = :id"
        );

        return $stmt->execute([
            ':nim' => $data['nim'],
            ':nama' => $data['nama'],
            ':prodi' => $data['prodi'],
            ':foto' => $data['foto'] ?? null,
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM mahasiswa WHERE id = :id"
        );

        return $stmt->execute([':id' => $id]);
    }

    public function countAll(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM mahasiswa"
        );

        return (int) $stmt->fetchColumn();
    }
}
