
<?php

require_once __DIR__ . '/../Core/BaseModel.php';
require_once __DIR__ . '/Mahasiswa.php';

class MahasiswaRepository extends BaseModel
{
    public function all(): array
    {
        $sql = "SELECT 
                    m.*,
                    p.nama AS nama_prodi
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                ORDER BY m.nim ASC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM mahasiswa WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function create(Mahasiswa $mahasiswa): int
    {
        $sql = "INSERT INTO mahasiswa
                    (nim, nama, email, prodi_id, angkatan)
                VALUES
                    (:nim, :nama, :email, :prodi_id, :angkatan)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'email' => $mahasiswa->getEmail(),
            'prodi_id' => $mahasiswa->getProdiId(),
            'angkatan' => $mahasiswa->getAngkatan()
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Mahasiswa $mahasiswa): bool
    {
        $sql = "UPDATE mahasiswa SET
                    nim = :nim,
                    nama = :nama,
                    email = :email,
                    prodi_id = :prodi_id,
                    angkatan = :angkatan
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'email' => $mahasiswa->getEmail(),
            'prodi_id' => $mahasiswa->getProdiId(),
            'angkatan' => $mahasiswa->getAngkatan(),
            'id' => $mahasiswa->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM mahasiswa WHERE id = :id"
        );

        return $stmt->execute(['id' => $id]);
    }

    public function getProdi(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id, nama FROM prodi ORDER BY nama ASC"
        );

        return $stmt->fetchAll();
    }
}