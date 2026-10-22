
<?php

class Mahasiswa
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll(): array
    {
        $sql = 'SELECT id, nim, nama, prodi, created_at
                FROM mahasiswa
                ORDER BY id ASC';

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $sql = 'SELECT id, nim, nama, prodi, created_at
                FROM mahasiswa
                WHERE id = :id
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    public function create(array $data): string
    {
        try {
            $sql = 'INSERT INTO mahasiswa (nim, nama, prodi)
                    VALUES (:nim, :nama, :prodi)';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nim' => $data['nim'],
                ':nama' => $data['nama'],
                ':prodi' => $data['prodi']
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log('Gagal menambahkan mahasiswa: ' . $e->getMessage());

            throw new RuntimeException(
                'Terjadi kesalahan pada server.'
            );
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $sql = 'UPDATE mahasiswa
                    SET nim = :nim, nama = :nama, prodi = :prodi
                    WHERE id = :id';

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':nim' => $data['nim'],
                ':nama' => $data['nama'],
                ':prodi' => $data['prodi'],
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log('Gagal mengubah mahasiswa: ' . $e->getMessage());

            throw new RuntimeException(
                'Terjadi kesalahan pada server.'
            );
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = 'DELETE FROM mahasiswa WHERE id = :id';

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log('Gagal menghapus mahasiswa: ' . $e->getMessage());

            throw new RuntimeException(
                'Terjadi kesalahan pada server.'
            );
        }
    }
}
