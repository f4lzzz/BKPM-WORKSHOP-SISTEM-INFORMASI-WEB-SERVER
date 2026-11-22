# ACARA 24 - Integrasi API dengan Frontend

## Informasi Praktikum
- Mata Kuliah: Workshop Sistem Informasi Web Server
- Kode Mata Kuliah: TIF330805
- Acara: 24
- Pokok Bahasan: Integrasi API dengan Frontend
- Pertemuan: Minggu 12 / Pertemuan 2
- Nama: Mochammad Syifaul Qulub
- NIM: E41250219
- Program Studi: Teknik Informatika
- Institusi: Politeknik Negeri Jember Kampus 3 PSDKU Nganjuk

## Deskripsi Proyek
Proyek ini merupakan implementasi integrasi REST API dengan frontend menggunakan Fetch API. Aplikasi menampilkan data mahasiswa dari database MySQL secara dinamis melalui JavaScript dengan sintaks async/await.

## Teknologi
- PHP Native
- MySQL
- PDO
- HTML
- JavaScript
- Fetch API
- Async/Await
- Apache (Laragon)
- PowerShell
- Git dan GitHub

## Struktur Folder
```text
ACARA 24 Integrasi API dengan Frontend/
+-- app/
¦   +-- config/Database.php
¦   +-- controllers/MahasiswaController.php
¦   +-- core/Response.php
¦   +-- core/Router.php
¦   +-- models/Mahasiswa.php
+-- database/acara24.sql
+-- public/
¦   +-- index.php
¦   +-- .htaccess
¦   +-- views/
¦       +-- index.html
¦       +-- app.js
+-- README.md
```

## Endpoint API
| Method | Endpoint | Fungsi |
|---|---|---|
| GET | /mahasiswa | Mengambil seluruh data mahasiswa |

## Cara Menjalankan
1. Jalankan Apache dan MySQL melalui Laragon.
2. Import database/acara24.sql melalui phpMyAdmin.
3. Pastikan konfigurasi database pada app/config/Database.php sesuai.
4. Buka frontend melalui browser:
   http://localhost/BKPM%20WORKSHOP%20SISTEM%20INFORMASI%20WEB%20SERVER/ACARA%2024%20Integrasi%20API%20dengan%20Frontend/public/views/index.html

## Hasil Pengujian
- API GET /mahasiswa berhasil dengan HTTP 200 OK.
- Fetch API berhasil mengambil data dari backend.
- Data mahasiswa berhasil ditampilkan pada tabel frontend.
- Indikator Memuat data... berhasil diterapkan.
- Kondisi data kosong menampilkan Belum ada data.
- Penanganan error menggunakan try-catch.
- Tidak ditemukan error CORS yang menghalangi integrasi.

## Kesimpulan
Aplikasi berhasil mengintegrasikan REST API dengan frontend menggunakan Fetch API, async/await, dan penanganan error JavaScript.
