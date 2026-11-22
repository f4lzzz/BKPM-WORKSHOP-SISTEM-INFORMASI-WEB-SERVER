
<?php

class Mahasiswa
{
    private $conn;
    private $table = "mahasiswa";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nim, $nama, $prodi)
    {
        $query = "INSERT INTO " . $this->table . "
                  (nim, nama, prodi)
                  VALUES (:nim, :nama, :prodi)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nim", $nim);
        $stmt->bindParam(":nama", $nama);
        $stmt->bindParam(":prodi", $prodi);

        return $stmt->execute();
    }

    public function update($id, $nim, $nama, $prodi)
    {
        $query = "UPDATE " . $this->table . "
                  SET nim = :nim, nama = :nama, prodi = :prodi
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nim", $nim);
        $stmt->bindParam(":nama", $nama);
        $stmt->bindParam(":prodi", $prodi);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    public function countAll()
    {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}
