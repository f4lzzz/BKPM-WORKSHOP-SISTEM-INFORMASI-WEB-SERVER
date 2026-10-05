<?php

require_once __DIR__ . '/../Core/BaseModel.php';

class MahasiswaRepository extends BaseModel
{
    public function getAll(): array
    {
        $sql = "
            SELECT 
                mahasiswa.*,
                prodi.nama AS prodi_nama
            FROM mahasiswa
            INNER JOIN prodi ON mahasiswa.prodi_id = prodi.id
            ORDER BY mahasiswa.id DESC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                mahasiswa.*,
                prodi.nama AS prodi_nama
            FROM mahasiswa
            INNER JOIN prodi ON mahasiswa.prodi_id = prodi.id
            WHERE mahasiswa.id = ?
        ");

        $stmt->execute([$id]);

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function findByNim(string $nim, ?int $excludeId = null): ?array
    {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare("
                SELECT *
                FROM mahasiswa
                WHERE nim = ?
                AND id != ?
                LIMIT 1
            ");

            $stmt->execute([$nim, $excludeId]);
        } else {
            $stmt = $this->db->prepare("
                SELECT *
                FROM mahasiswa
                WHERE nim = ?
                LIMIT 1
            ");

            $stmt->execute([$nim]);
        }

        $data = $stmt->fetch();

        return $data ?: null;
    }

    public function create(Mahasiswa $mahasiswa): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO mahasiswa
                (nim, nama, email, prodi_id, angkatan)
            VALUES
                (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $mahasiswa->getNim(),
            $mahasiswa->getNama(),
            $mahasiswa->getEmail(),
            $mahasiswa->getProdiId(),
            $mahasiswa->getAngkatan()
        ]);
    }

    public function update(Mahasiswa $mahasiswa): bool
    {
        $stmt = $this->db->prepare("
            UPDATE mahasiswa
            SET
                nim = ?,
                nama = ?,
                email = ?,
                prodi_id = ?,
                angkatan = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $mahasiswa->getNim(),
            $mahasiswa->getNama(),
            $mahasiswa->getEmail(),
            $mahasiswa->getProdiId(),
            $mahasiswa->getAngkatan(),
            $mahasiswa->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM mahasiswa
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}