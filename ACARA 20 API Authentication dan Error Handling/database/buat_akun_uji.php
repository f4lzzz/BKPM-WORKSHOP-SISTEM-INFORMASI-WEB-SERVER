<?php

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=acara 20;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $username = 'faul20';
    $email = 'faul20@example.com';
    $password = password_hash('Faul12345!', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        'SELECT id FROM users WHERE username = :username'
    );
    $stmt->execute([':username' => $username]);

    $user = $stmt->fetch();

    if ($user) {
        $stmt = $pdo->prepare(
            'UPDATE users SET password = :password, role = :role
             WHERE username = :username'
        );
        $stmt->execute([
            ':password' => $password,
            ':role' => 'admin',
            ':username' => $username
        ]);
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO users (username, email, password, role)
             VALUES (:username, :email, :password, :role)'
        );
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
            ':role' => 'admin'
        ]);
    }

    echo "Akun pengujian berhasil disiapkan.\n";
    echo "Username: faul20\n";
    echo "Password: Faul12345!\n";
} catch (PDOException $e) {
    echo "Gagal menyiapkan akun: " . $e->getMessage() . "\n";
    exit(1);
}
