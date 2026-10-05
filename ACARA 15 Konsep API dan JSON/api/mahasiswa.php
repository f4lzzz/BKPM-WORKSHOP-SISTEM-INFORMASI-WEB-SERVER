<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';

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

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        // GET berdasarkan ID
        if (isset($_GET['id'])) {

            $id = filter_var(
                $_GET['id'],
                FILTER_VALIDATE_INT
            );

            if ($id === false || $id <= 0) {

                http_response_code(400);

                echo json_encode([
                    'success' => false,
                    'message' => 'ID tidak valid',
                    'data' => null
                ]);

                exit;
            }

            $stmt = $pdo->prepare(
                "SELECT id, nim, nama, email
                 FROM mahasiswa
                 WHERE id = :id"
            );

            $stmt->execute([
                'id' => $id
            ]);

            $data = $stmt->fetch();

            if (!$data) {

                http_response_code(404);

                echo json_encode([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan',
                    'data' => null
                ]);

                exit;
            }

            echo json_encode([
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data' => $data
            ]);

            exit;
        }

        // GET semua data
        $stmt = $pdo->prepare(
            "SELECT id, nim, nama, email
             FROM mahasiswa
             ORDER BY id ASC"
        );

        $stmt->execute();

        $data = $stmt->fetchAll();

        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $data
        ]);

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | POST
    |--------------------------------------------------------------------------
    */

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Membaca JSON dari request body
        $json = file_get_contents('php://input');

        $input = json_decode($json, true);

        // Cek JSON
        if (!is_array($input)) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Format JSON tidak valid',
                'data' => null
            ]);

            exit;
        }

        // Ambil data
        $nim = trim($input['nim'] ?? '');
        $nama = trim($input['nama'] ?? '');
        $email = trim($input['email'] ?? '');

        // Validasi data kosong
        if ($nim === '' || $nama === '' || $email === '') {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'NIM, nama, dan email wajib diisi',
                'data' => null
            ]);

            exit;
        }

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'Format email tidak valid',
                'data' => null
            ]);

            exit;
        }

        // Cek NIM
        $check = $pdo->prepare(
            "SELECT COUNT(*)
             FROM mahasiswa
             WHERE nim = :nim"
        );

        $check->execute([
            'nim' => $nim
        ]);

        if ((int) $check->fetchColumn() > 0) {

            http_response_code(409);

            echo json_encode([
                'success' => false,
                'message' => 'NIM sudah terdaftar',
                'data' => null
            ]);

            exit;
        }

        // Insert data
        $stmt = $pdo->prepare(
            "INSERT INTO mahasiswa
            (nim, nama, email)
            VALUES
            (:nim, :nama, :email)"
        );

        $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email
        ]);

        $id = (int) $pdo->lastInsertId();

        http_response_code(201);

        echo json_encode([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data' => [
                'id' => $id,
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email
            ]
        ]);

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | METHOD LAIN
    |--------------------------------------------------------------------------
    */

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method tidak didukung',
        'data' => null
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server',
        'data' => null
    ]);
}