# DOKUMENTASI PENGUJIAN API
## Acara 22 - Dokumentasi Endpoint

### 1. Informasi Pengujian

- Nama API: API Mahasiswa - Acara 22
- Teknologi: PHP Native dan MySQL
- Dokumentasi: OpenAPI 3.0.3
- Pengujian: PowerShell Invoke-RestMethod
- Autentikasi: JSON Web Token (JWT)

### 2. Daftar Pengujian

| No | Endpoint | Metode | Skenario | Hasil yang Diharapkan |
|---|---|---|---|---|
| 1 | /mahasiswa | GET | Mengambil seluruh data | HTTP 200 |
| 2 | /login | POST | Login admin dengan akun valid | HTTP 200 |
| 3 | /mahasiswa | POST | Menambahkan mahasiswa dengan token | HTTP 201 |
| 4 | /mahasiswa/4 | PUT | Memperbarui data mahasiswa | HTTP 200 |
| 5 | /mahasiswa/4 | DELETE | Menghapus data mahasiswa | HTTP 200 |
| 6 | /mahasiswa | POST | Mengirim permintaan tanpa token | HTTP 401 |
| 7 | /mahasiswa | POST | Mengirim token palsu | HTTP 401 |
| 8 | /mahasiswa | POST | Mengirim data tidak lengkap | HTTP 400 |
| 9 | /mahasiswa/9999 | PUT | Memperbarui ID yang tidak tersedia | HTTP 404 |
| 10 | /mahasiswa/9999 | DELETE | Menghapus ID yang tidak tersedia | HTTP 404 |
| 11 | /register | POST | Registrasi pengguna baru | HTTP 201 |
| 12 | /login | POST | Login pengguna dengan akun valid | HTTP 200 |
| 13 | /login | POST | Login dengan password salah | HTTP 401 |
| 14 | PHP Syntax Check | CLI | Memeriksa sintaks seluruh file PHP | Tidak ada kesalahan sintaks |
| 15 | Swagger Editor | OpenAPI | Memvalidasi dokumentasi endpoint | Dokumentasi berhasil ditampilkan |

### 3. Hasil Pengujian

Seluruh skenario pengujian telah dijalankan menggunakan PowerShell.

Hasil pengujian menunjukkan bahwa:
- API dapat mengambil data mahasiswa.
- API dapat menambahkan, memperbarui, dan menghapus data mahasiswa.
- Sistem autentikasi JWT dapat menghasilkan dan memvalidasi token.
- Sistem menolak permintaan tanpa token.
- Sistem menolak token yang tidak valid.
- Validasi input berjalan dengan baik.
- API memberikan respons yang sesuai ketika data tidak ditemukan.
- Registrasi dan login pengguna berhasil.
- Login dengan password yang salah ditolak.
- Seluruh file PHP lolos pemeriksaan sintaks.
- Dokumentasi OpenAPI berhasil ditampilkan melalui Swagger Editor.

### 4. Kesimpulan

Berdasarkan pengujian yang telah dilakukan, REST API Mahasiswa pada Acara 22 berhasil dibangun menggunakan PHP Native dan MySQL.

Dokumentasi endpoint telah disusun menggunakan OpenAPI Specification versi 3.0.3 dan berhasil ditampilkan melalui Swagger Editor.

Pengujian menunjukkan bahwa fungsi CRUD, autentikasi JWT, validasi input, dan penanganan kesalahan berjalan sesuai skenario yang diuji.
