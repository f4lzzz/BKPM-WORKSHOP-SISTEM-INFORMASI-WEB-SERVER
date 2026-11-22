# API Mahasiswa - Acara 22

REST API untuk pengelolaan data mahasiswa menggunakan PHP Native dan MySQL.

## Teknologi
- PHP Native
- MySQL
- PDO
- JSON Web Token (JWT)
- OpenAPI Specification 3.0.3
- Swagger Editor

## Struktur Proyek
- app/config: konfigurasi database
- app/controllers: pengelolaan request API
- app/core: router, response, dan autentikasi
- app/models: model data mahasiswa
- database: file SQL
- docs: dokumentasi API dan pengujian
- public: direktori utama API

## Instalasi
1. Letakkan folder proyek di direktori www Laragon.
2. Jalankan Apache dan MySQL.
3. Import database/acara22.sql melalui phpMyAdmin.
4. Import database/admin.sql untuk membuat akun administrator.
5. Pastikan konfigurasi database pada app/config/Database.php sesuai.
6. Akses API melalui browser atau PowerShell.

## Endpoint API

| Method | Endpoint | Keterangan |
|---|---|---|
| POST | /register | Registrasi pengguna |
| POST | /login | Login pengguna |
| GET | /mahasiswa | Mengambil seluruh mahasiswa |
| POST | /mahasiswa | Menambahkan mahasiswa |
| PUT | /mahasiswa/{id} | Memperbarui mahasiswa |
| DELETE | /mahasiswa/{id} | Menghapus mahasiswa |

## Autentikasi
Endpoint POST, PUT, dan DELETE mahasiswa memerlukan JWT Bearer Token.

Format header:

Authorization: Bearer <token>

## Dokumentasi
- docs/openapi.yaml: dokumentasi OpenAPI.
- docs/API_TESTING.md: dokumentasi pengujian API.

## Pengembang
Acara 22 - Workshop Sistem Informasi Web Server
Program Studi Teknik Informatika
Politeknik Negeri Jember
