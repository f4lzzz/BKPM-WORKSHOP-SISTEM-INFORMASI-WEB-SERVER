# API Testing Documentation — Acara 21

## 1. Informasi Pengujian

- Nama API: API Mahasiswa Acara 21
- Metode pengujian: PowerShell
- Format request: JSON
- Autentikasi: JWT Bearer Token
- Database: MySQL

## 2. Hasil Pengujian

| No. | Endpoint | Method | HTTP Status | Hasil |
|---|---|---|---|---|
| 1 | /register | POST | 201 | Berhasil |
| 2 | /register | POST | 409 | Duplikasi ditolak |
| 3 | /login | POST | 200 | Berhasil |
| 4 | /mahasiswa | GET | 200 | Berhasil |
| 5 | /mahasiswa | POST | 201 | Berhasil |
| 6 | /mahasiswa/{id} | PUT | 200 | Berhasil |
| 7 | /mahasiswa/{id} | DELETE | 200 | Berhasil |
| 8 | /mahasiswa | POST | 401 | Tanpa token ditolak |
| 9 | /mahasiswa | POST | 401 | Token palsu ditolak |
| 10 | /mahasiswa | POST | 400 | Input tidak lengkap ditolak |
| 11 | /mahasiswa/999 | PUT | 404 | Data tidak ditemukan |

## 3. Contoh Request

### Register
Method: POST
Endpoint: /register

Body:
- username: faul21
- email: faul21@example.com
- password: disamarkan

Response: Registrasi berhasil.
HTTP Status: 201 Created.

### Login
Method: POST
Endpoint: /login

Body:
- username: faul21
- password: disamarkan

Response: Login berhasil dan JWT diterbitkan.
HTTP Status: 200 OK.

### Get Mahasiswa
Method: GET
Endpoint: /mahasiswa

Response: Daftar mahasiswa berhasil ditampilkan.
HTTP Status: 200 OK.

### Store Mahasiswa
Method: POST
Endpoint: /mahasiswa
Authentication: Bearer Token.

Body:
- nim: 260210004
- nama: Mahasiswa Pengujian
- prodi: Teknik Informatika

Response: Data mahasiswa berhasil ditambahkan.
HTTP Status: 201 Created.

### Update Mahasiswa
Method: PUT
Endpoint: /mahasiswa/4
Authentication: Bearer Token.

Body:
- nim: 260210004
- nama: Faul Mahasiswa Update
- prodi: Teknik Informatika

Response: Data mahasiswa berhasil diubah.
HTTP Status: 200 OK.

### Delete Mahasiswa
Method: DELETE
Endpoint: /mahasiswa/4
Authentication: Bearer Token.

Response: Data mahasiswa berhasil dihapus.
HTTP Status: 200 OK.

## 4. Pengujian Error

| Skenario | Response | HTTP Status |
|---|---|---|
| Tanpa token | Token tidak ditemukan | 401 |
| Token palsu | Format token tidak valid | 401 |
| Data tidak lengkap | NIM, nama, dan prodi wajib diisi | 400 |
| ID tidak ditemukan | Data mahasiswa tidak ditemukan | 404 |
| Username/email duplikat | Username atau email sudah digunakan | 409 |

## 5. Kesimpulan

Pengujian API Acara 21 dilakukan menggunakan PowerShell. Seluruh endpoint utama berhasil diuji, meliputi registrasi, login, menampilkan data, menambahkan data, mengubah data, dan menghapus data mahasiswa.

Pengujian juga membuktikan bahwa autentikasi JWT, validasi input, dan penanganan kesalahan telah berjalan sesuai hasil yang diharapkan.
