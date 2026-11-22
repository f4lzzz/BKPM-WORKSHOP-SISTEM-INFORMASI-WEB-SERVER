CREATE DATABASE IF NOT EXISTS `acara 22`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `acara 22`;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO mahasiswa (nim, nama, prodi) VALUES
('260220001', 'Budi Santoso', 'Teknik Informatika'),
('260220002', 'Andi Pratama', 'Manajemen Informatika'),
('260220003', 'Siti Aminah', 'Teknik Informatika');
