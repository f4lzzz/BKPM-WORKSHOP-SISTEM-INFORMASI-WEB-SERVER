
<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

function response(int $status, bool $success, string $message, mixed $data = null): void
{
    http_response_code($status);

    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    exit;
}

try {
    $config = require __DIR__ . '/../config/database.php';

    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

    $pdo = new PDO(
        $dsn,
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    $method = $_SERVER['REQUEST_METHOD'];
    $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

    if (isset($_GET['id']) && ($id === false || $id <= 0)) {
        response(400, false, 'ID tidak valid');
    }

    // GET: mengambil seluruh data atau berdasarkan ID
    if ($method === 'GET') {
        if ($id !== null) {
            $stmt = $pdo->prepare(
                'SELECT id, nim, nama, email FROM mahasiswa WHERE id = :id'
            );
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch();

            if (!$data) {
                response(404, false, 'Data mahasiswa tidak ditemukan');
            }

            response(200, true, 'Data berhasil diambil', $data);
        }

        $stmt = $pdo->query(
            'SELECT id, nim, nama, email FROM mahasiswa ORDER BY id ASC'
        );

        response(200, true, 'Data berhasil diambil', $stmt->fetchAll());
    }

    // POST: menambahkan mahasiswa
    if ($method === 'POST') {
        if ($id !== null) {
            response(400, false, 'POST tidak menggunakan ID pada endpoint');
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
            response(400, false, 'Format JSON tidak valid');
        }

        $nim = trim($input['nim'] ?? '');
        $nama = trim($input['nama'] ?? '');
        $email = trim($input['email'] ?? '');

        if ($nim === '' || $nama === '' || $email === '') {
            response(400, false, 'NIM, nama, dan email wajib diisi');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            response(400, false, 'Format email tidak valid');
        }

        $check = $pdo->prepare('SELECT id FROM mahasiswa WHERE nim = :nim');
        $check->execute(['nim' => $nim]);

        if ($check->fetch()) {
            response(409, false, 'NIM sudah terdaftar');
        }

        $stmt = $pdo->prepare(
            'INSERT INTO mahasiswa (nim, nama, email)
             VALUES (:nim, :nama, :email)'
        );

        $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email
        ]);

        $newId = (int) $pdo->lastInsertId();

        response(201, true, 'Data mahasiswa berhasil ditambahkan', [
            'id' => $newId,
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email
        ]);
    }

    // PUT: mengubah seluruh data mahasiswa
    if ($method === 'PUT') {
        if ($id === null) {
            response(400, false, 'ID mahasiswa wajib disertakan');
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
            response(400, false, 'Format JSON tidak valid');
        }

        $nim = trim($input['nim'] ?? '');
        $nama = trim($input['nama'] ?? '');
        $email = trim($input['email'] ?? '');

        if ($nim === '' || $nama === '' || $email === '') {
            response(400, false, 'NIM, nama, dan email wajib diisi');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            response(400, false, 'Format email tidak valid');
        }

        $check = $pdo->prepare('SELECT id FROM mahasiswa WHERE id = :id');
        $check->execute(['id' => $id]);

        if (!$check->fetch()) {
            response(404, false, 'Data mahasiswa tidak ditemukan');
        }

        $checkNim = $pdo->prepare(
            'SELECT id FROM mahasiswa WHERE nim = :nim AND id <> :id'
        );
        $checkNim->execute(['nim' => $nim, 'id' => $id]);

        if ($checkNim->fetch()) {
            response(409, false, 'NIM sudah digunakan mahasiswa lain');
        }

        $stmt = $pdo->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email
             WHERE id = :id'
        );

        $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'id' => $id
        ]);

        response(200, true, 'Data mahasiswa berhasil diperbarui', [
            'id' => $id,
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email
        ]);
    }

    // DELETE: menghapus mahasiswa
    if ($method === 'DELETE') {
        if ($id === null) {
            response(400, false, 'ID mahasiswa wajib disertakan');
        }

        $stmt = $pdo->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() === 0) {
            response(404, false, 'Data mahasiswa tidak ditemukan');
        }

        response(200, true, 'Data mahasiswa berhasil dihapus', [
            'id' => $id
        ]);
    }

    header('Allow: GET, POST, PUT, DELETE');
    response(405, false, 'Method tidak didukung');

} catch (PDOException $e) {
    response(500, false, 'Terjadi kesalahan pada server');
}
