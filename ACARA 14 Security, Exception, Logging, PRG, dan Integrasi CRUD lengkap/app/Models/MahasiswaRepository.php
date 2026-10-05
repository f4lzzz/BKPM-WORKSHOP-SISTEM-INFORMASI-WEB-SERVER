<?php

require_once __DIR__ . '/../Core/BaseModel.php';
require_once __DIR__ . '/Mahasiswa.php';

class MahasiswaRepository extends BaseModel
{
    public function all(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM mahasiswa ORDER BY id DESC"
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM mahasiswa WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function existsByNim(string $nim, ?int $excludeId = null): bool
    {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM mahasiswa
                 WHERE nim = :nim AND id != :id"
            );

            $stmt->execute([
                'nim' => $nim,
                'id' => $excludeId
            ]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM mahasiswa
                 WHERE nim = :nim"
            );

            $stmt->execute([
                'nim' => $nim
            ]);
        }

        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(Mahasiswa $mahasiswa): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO mahasiswa
            (nim, nama, email, angkatan)
            VALUES
            (:nim, :nama, :email, :angkatan)"
        );

        $stmt->execute([
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'email' => $mahasiswa->getEmail(),
            'angkatan' => $mahasiswa->getAngkatan()
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(Mahasiswa $mahasiswa): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE mahasiswa
             SET nim = :nim,
                 nama = :nama,
                 email = :email,
                 angkatan = :angkatan
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $mahasiswa->getId(),
            'nim' => $mahasiswa->getNim(),
            'nama' => $mahasiswa->getNama(),
            'email' => $mahasiswa->getEmail(),
            'angkatan' => $mahasiswa->getAngkatan()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM mahasiswa WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}