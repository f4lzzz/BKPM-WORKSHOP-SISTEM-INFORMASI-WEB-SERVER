# API Documentation — Acara 21

## 1. Informasi API

| Informasi | Keterangan |
|---|---|
| Nama API | API Mahasiswa Acara 21 |
| Versi | 1.0.0 |
| Arsitektur | REST API |
| Format data | JSON |
| Autentikasi | JWT Bearer Token |
| Database | MySQL |
| Bahasa pemrograman | PHP |

### Base URL

```text
http://localhost/BKPM%20WORKSHOP%20SISTEM%20INFORMASI%20WEB%20SERVER/ACARA%2021%20Dokumentasi%20Endpoint/public
```

## 2. Ringkasan Endpoint

| No. | Method | Endpoint | Controller | Fungsi | Auth |
|---|---|---|---|---|---|
| 1 | POST | /register | AuthController | Registrasi pengguna | Tidak |
| 2 | POST | /login | AuthController | Login pengguna | Tidak |
| 3 | GET | /mahasiswa | MahasiswaController | Menampilkan seluruh mahasiswa | Tidak |
| 4 | POST | /mahasiswa | MahasiswaController | Menambahkan mahasiswa | Ya |
| 5 | PUT | /mahasiswa/{id} | MahasiswaController | Mengubah mahasiswa | Ya |
| 6 | DELETE | /mahasiswa/{id} | MahasiswaController | Menghapus mahasiswa | Ya |

## 3. Autentikasi

API menggunakan JSON Web Token (JWT) sebagai mekanisme autentikasi.

Token diperoleh melalui endpoint login.

Endpoint yang membutuhkan autentikasi:
- POST /mahasiswa
- PUT /mahasiswa/{id}
- DELETE /mahasiswa/{id}

Tambahkan header berikut pada setiap request yang membutuhkan autentikasi:

```http
Authorization: Bearer TOKEN_JWT
Content-Type: application/json
```

Masa berlaku token adalah 3600 detik atau 1 jam.

Endpoint GET /mahasiswa dapat diakses tanpa token.

## 4. Detail Endpoint

### 4.1 Registrasi Pengguna

**Endpoint:** `/register`

**Method:** `POST`

**Controller:** `AuthController::register()`

**Autentikasi:** Tidak diperlukan.

Request Body:

```json
{
    "username": "faul21",
    "email": "faul21@example.com",
    "password": "Password123!"
}
```

Response berhasil (201 Created):

```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "id": "2",
        "username": "faul21",
        "email": "faul21@example.com",
        "role": "user"
    }
}
```

Kemungkinan error:
- 400 Bad Request: Format JSON tidak valid, data wajib belum lengkap, email tidak valid, atau password kurang dari 8 karakter.
- 409 Conflict: Username atau email sudah digunakan.
- 500 Internal Server Error: Terjadi kesalahan pada server.

### 4.2 Login Pengguna

**Endpoint:** `/login`

**Method:** `POST`

**Controller:** `AuthController::login()`

**Autentikasi:** Tidak diperlukan.

Request Body:

```json
{
    "username": "faul21",
    "password": "Faul21#User2026!"
}
```

Response berhasil (200 OK):

```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "token": "JWT_TOKEN",
        "token_type": "Bearer",
        "expires_in": 3600,
        "role": "user"
    }
}
```

Kemungkinan error:
- 400 Bad Request: Format JSON tidak valid atau username dan password belum diisi.
- 401 Unauthorized: Username atau password salah.
- 500 Internal Server Error: Terjadi kesalahan pada server.

### 4.3 Menampilkan Seluruh Data Mahasiswa

**Endpoint:** `/mahasiswa`

**Method:** `GET`

**Controller:** `MahasiswaController::index()`

**Autentikasi:** Tidak diperlukan.

Request Body: Tidak ada.

Response berhasil (200 OK):

```json
{
    "success": true,
    "message": "Data mahasiswa berhasil diambil",
    "data": [
        {
            "id": 1,
            "nim": "260210001",
            "nama": "Budi Santoso",
            "prodi": "Teknik Informatika",
            "created_at": "2026-11-19 09:35:21"
        },
        {
            "id": 2,
            "nim": "260210002",
            "nama": "Andi Pratama",
            "prodi": "Manajemen Informatika",
            "created_at": "2026-11-19 09:35:21"
        }
    ]
}
```

Kemungkinan error:
- 500 Internal Server Error: Terjadi kesalahan pada server.

### 4.4 Menambahkan Data Mahasiswa

**Endpoint:** `/mahasiswa`

**Method:** `POST`

**Controller:** `MahasiswaController::store()`

**Autentikasi:** Wajib menggunakan JWT Bearer Token.

Request Header:

```http
Authorization: Bearer JWT_TOKEN
Content-Type: application/json
```

Request Body:

```json
{
    "nim": "260210004",
    "nama": "Mahasiswa Pengujian",
    "prodi": "Teknik Informatika"
}
```

Response berhasil (201 Created):

```json
{
    "success": true,
    "message": "Data mahasiswa berhasil ditambahkan",
    "data": {
        "id": "4"
    }
}
```

Kemungkinan error:
- 400 Bad Request: Format JSON tidak valid atau NIM, nama, dan prodi belum diisi.
- 401 Unauthorized: Token tidak ditemukan, tidak valid, atau kedaluwarsa.
- 409 Conflict: NIM sudah terdaftar.
- 500 Internal Server Error: Terjadi kesalahan pada server.

### 4.5 Mengubah Data Mahasiswa

**Endpoint:** `/mahasiswa/{id}`

**Method:** `PUT`

**Controller:** `MahasiswaController::update($id)`

**Autentikasi:** Wajib menggunakan JWT Bearer Token.

Contoh endpoint:

```text
/mahasiswa/4
```

Request Header:

```http
Authorization: Bearer JWT_TOKEN
Content-Type: application/json
```

Request Body:

```json
{
    "nim": "260210004",
    "nama": "Faul Mahasiswa Update",
    "prodi": "Teknik Informatika"
}
```

Response berhasil (200 OK):

```json
{
    "success": true,
    "message": "Data mahasiswa berhasil diubah"
}
```

Kemungkinan error:
- 400 Bad Request: ID tidak valid, format JSON tidak valid, atau data wajib belum lengkap.
- 401 Unauthorized: Token tidak ditemukan, tidak valid, atau kedaluwarsa.
- 404 Not Found: Data mahasiswa tidak ditemukan.
- 409 Conflict: NIM sudah digunakan mahasiswa lain.
- 500 Internal Server Error: Terjadi kesalahan pada server.

### 4.6 Menghapus Data Mahasiswa

**Endpoint:** `/mahasiswa/{id}`

**Method:** `DELETE`

**Controller:** `MahasiswaController::destroy($id)`

**Autentikasi:** Wajib menggunakan JWT Bearer Token.

Contoh endpoint:

```text
/mahasiswa/4
```

Request Header:

```http
Authorization: Bearer JWT_TOKEN
```

Request Body: Tidak ada.

Response berhasil (200 OK):

```json
{
    "success": true,
    "message": "Data mahasiswa berhasil dihapus"
}
```

Kemungkinan error:
- 400 Bad Request: ID mahasiswa tidak valid.
- 401 Unauthorized: Token tidak ditemukan, tidak valid, atau kedaluwarsa.
- 404 Not Found: Data mahasiswa tidak ditemukan.
- 500 Internal Server Error: Terjadi kesalahan pada server.

## 5. Daftar HTTP Status Code

| Status Code | Nama | Keterangan |
|---|---|---|
| 200 | OK | Request berhasil diproses |
| 201 | Created | Data berhasil dibuat |
| 400 | Bad Request | Request atau input tidak valid |
| 401 | Unauthorized | Autentikasi gagal atau token tidak valid |
| 404 | Not Found | Endpoint atau data tidak ditemukan |
| 405 | Method Not Allowed | Method HTTP tidak diizinkan |
| 409 | Conflict | Terjadi konflik data, seperti duplikasi |
| 500 | Internal Server Error | Kesalahan pada server |

## 6. Pemetaan Controller dan Method

### AuthController

| Method Controller | Endpoint | HTTP Method |
|---|---|---|
| register() | /register | POST |
| login() | /login | POST |

### MahasiswaController

| Method Controller | Endpoint | HTTP Method |
|---|---|---|
| index() | /mahasiswa | GET |
| store() | /mahasiswa | POST |
| update($id) | /mahasiswa/{id} | PUT |
| destroy($id) | /mahasiswa/{id} | DELETE |

## 7. Catatan Pengujian

Pengujian endpoint dilakukan menggunakan PowerShell dengan metode HTTP request.

Hasil pengujian yang telah dilakukan:

| No. | Skenario Pengujian | Hasil |
|---|---|---|
| 1 | Registrasi pengguna baru | Berhasil |
| 2 | Registrasi username/email duplikat | Berhasil ditolak |
| 3 | Login dengan akun terdaftar | Berhasil |
| 4 | Mengambil seluruh data mahasiswa | Berhasil |
| 5 | Menambahkan data mahasiswa dengan token | Berhasil |
| 6 | Mengubah data mahasiswa dengan token | Berhasil |
| 7 | Menghapus data mahasiswa dengan token | Berhasil |
| 8 | Menambahkan data tanpa token | Ditolak |
| 9 | Menggunakan token palsu | Ditolak |
| 10 | Mengirim data mahasiswa tidak lengkap | Ditolak |
| 11 | Mengubah mahasiswa dengan ID tidak ditemukan | Ditolak |

## 8. Kesimpulan

API Mahasiswa Acara 21 telah menerapkan REST API dengan metode HTTP GET, POST, PUT, dan DELETE.

API menyediakan fitur registrasi dan login menggunakan AuthController serta pengelolaan data mahasiswa menggunakan MahasiswaController.

Sistem autentikasi menggunakan JWT Bearer Token untuk melindungi endpoint penambahan, perubahan, dan penghapusan data mahasiswa. Validasi input dan penanganan HTTP status code juga telah diterapkan.

Dokumentasi ini menjelaskan endpoint, controller, format request, response, autentikasi, serta skenario pengujian API.
