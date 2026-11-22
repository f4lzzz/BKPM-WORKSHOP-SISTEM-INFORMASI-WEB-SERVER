# ACARA 23 - MANUAL TESTING DAN BLACKBOX TESTING

## Informasi Praktikum
- Mata Kuliah: Workshop Sistem Informasi Web Server
- Pokok Bahasan: Manual Testing dan Blackbox Testing
- Minggu: 12 / Pertemuan 1
- Mahasiswa: Mochammad Syifaul Qulub
- NIM: E41250219
- Program Studi: Teknik Informatika
- Institusi: Politeknik Negeri Jember

## Deskripsi Proyek
RESTful API sederhana untuk pengelolaan data mahasiswa menggunakan PHP Native dan MySQL. Pengujian dilakukan menggunakan metode Manual Testing dan Blackbox Testing.

## Teknologi
- PHP Native
- MySQL
- PDO
- Apache (Laragon)
- PowerShell
- JSON
- JWT Authentication

## Struktur Folder
- app/config/Database.php
- app/controllers/AuthController.php
- app/controllers/MahasiswaController.php
- app/core/Auth.php
- app/core/Response.php
- app/core/Router.php
- app/models/Mahasiswa.php
- database/
- docs/TEST_CASE.md
- public/.htaccess
- public/index.php
- README.md

## Konfigurasi Database
- Nama database: acara 23
- DBMS: MySQL
- Koneksi: PDO
- File konfigurasi: app/config/Database.php

## Endpoint API

| Method | Endpoint | Fungsi | Autentikasi |
|---|---|---|---|
| POST | /register | Registrasi pengguna | Tidak |
| POST | /login | Login pengguna | Tidak |
| GET | /mahasiswa | Mengambil data mahasiswa | Tidak |
| POST | /mahasiswa | Menambahkan mahasiswa | Ya |
| PUT | /mahasiswa/{id} | Memperbarui mahasiswa | Ya |
| DELETE | /mahasiswa/{id} | Menghapus mahasiswa | Ya |

## Pengujian
Pengujian dilakukan menggunakan PowerShell dengan Invoke-RestMethod dan pemeriksaan sintaks PHP menggunakan php -l.

Dokumentasi skenario pengujian tersedia pada docs/TEST_CASE.md.

- Total test case: 13
- Hasil pengujian: 13 PASS
- Pemeriksaan statis: 8 file PHP berhasil diperiksa tanpa kesalahan sintaks.

## Cara Menjalankan
1. Jalankan Apache dan MySQL melalui Laragon.
2. Pastikan database acara 23 tersedia.
3. Letakkan proyek di direktori www Laragon.
4. Akses endpoint melalui browser atau PowerShell.
5. Gunakan endpoint register dan login untuk memperoleh token.
6. Sertakan token Bearer pada endpoint yang membutuhkan autentikasi.

## Kesimpulan
RESTful API Mahasiswa telah dibuat dan diuji menggunakan metode Manual Testing dan Blackbox Testing. Pengujian mencakup autentikasi, validasi input, penanganan data duplikat, serta operasi CRUD mahasiswa.
