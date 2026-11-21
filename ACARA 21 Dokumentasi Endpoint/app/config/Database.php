<?php

class Database
{
    private string $host = 'localhost';
    private string $db_name = 'acara 21';
    private string $username = 'root';
    private string $password = '';
    public ?PDO $conn = null;

    public function getConnection(): ?PDO
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            error_log('Koneksi database gagal: ' . $e->getMessage());
        }

        return $this->conn;
    }
}
