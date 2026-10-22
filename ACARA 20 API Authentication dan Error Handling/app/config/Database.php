
<?php

class Database
{
    private string $host = 'localhost';
    private string $dbname = 'acara 20';
    private string $username = 'root';
    private string $password = '';

    public function getConnection(): PDO
    {
        try {
            $connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $connection->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            return $connection;
        } catch (PDOException $e) {
            throw new RuntimeException(
                'Koneksi database gagal.',
                0,
                $e
            );
        }
    }
}
