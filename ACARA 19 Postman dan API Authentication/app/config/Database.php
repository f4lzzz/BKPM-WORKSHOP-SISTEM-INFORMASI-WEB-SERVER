
<?php

class Database
{
    private string $host = 'localhost';
    private string $db_name = 'acara 19';
    private string $username = 'root';
    private string $password = '';

    public function connect(): PDO
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";

            $pdo = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

            return $pdo;
        } catch (PDOException $e) {
            http_response_code(500);

            die(json_encode([
                'success' => false,
                'message' => 'Koneksi database gagal'
            ]));
        }
    }
}
