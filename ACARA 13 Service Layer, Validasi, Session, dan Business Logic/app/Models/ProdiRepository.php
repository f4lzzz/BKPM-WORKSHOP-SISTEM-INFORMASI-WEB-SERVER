<?php

require_once __DIR__ . '/../Core/BaseModel.php';

class ProdiRepository extends BaseModel
{
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM prodi
            ORDER BY nama ASC
        ");

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM prodi
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $data = $stmt->fetch();

        return $data ?: null;
    }
}