
<?php

class Database
{
    private $host = "localhost";
    private $db_name = "faup1755_acara28";
    private $username = "faup1755_faul_acara28";
    private $password = "Faul291106";

    public function getConnection()
    {
        try {
            $conn = new PDO(
                "mysql:host=" . $this->host .
                ";dbname=" . $this->db_name .
                ";charset=utf8mb4",
                $this->username,
                $this->password
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $conn;

        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Koneksi database gagal.");
        }
    }
}
