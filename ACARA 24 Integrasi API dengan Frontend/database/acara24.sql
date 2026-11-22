CREATE DATABASE IF NOT EXISTS `acara 24`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `acara 24`;

CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO mahasiswa (nim, nama, prodi) VALUES
("260240001", "Budi Santoso", "Teknik Informatika"),
("260240002", "Andi Pratama", "Manajemen Informatika"),
("260240003", "Siti Aminah", "Teknik Informatika");
