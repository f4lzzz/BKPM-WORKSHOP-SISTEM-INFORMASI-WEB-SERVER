CREATE DATABASE IF NOT EXISTS `acara 23`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `acara 23`;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO mahasiswa (nim, nama, prodi) VALUES
('260230001', 'Budi Santoso', 'Teknik Informatika'),
('260230002', 'Andi Pratama', 'Manajemen Informatika'),
('260230003', 'Siti Aminah', 'Teknik Informatika');
