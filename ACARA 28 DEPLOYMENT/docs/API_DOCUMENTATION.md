# Dokumentasi API – Sistem Informasi Akademik

## 1. Informasi Umum

| Properti | Nilai |
|---|---|
| Nama aplikasi | Sistem Informasi Akademik |
| Base URL produksi | `https://faulacara28.my.id` |
| Format respons | JSON |
| Autentikasi | Bearer Token (JWT) |
| Algoritma JWT | HS256 |
| Masa berlaku token | 3600 detik (1 jam) |

Seluruh endpoint API diakses melalui HTTPS. Endpoint yang memerlukan autentikasi harus menyertakan header:

```http
Authorization: Bearer <token>
```

Untuk request JSON, gunakan:

```http
Content-Type: application/json
```

Untuk upload foto, gunakan `multipart/form-data`. Jangan menetapkan header `Content-Type` secara manual pada Fetch API ketika mengirim `FormData`, agar browser dapat menambahkan boundary.

## 2. Format Respons

Respons API menggunakan struktur JSON berikut:

```json
{
  "success": true,
  "message": "Pesan respons",
  "data": null
}
```

- `success`: `true` jika operasi berhasil dan `false` jika gagal.
- `message`: keterangan hasil operasi.
- `data`: data hasil operasi atau `null`.

## 3. Daftar Endpoint

| Method | Endpoint | Autentikasi | Hak akses | Keterangan |
|---|---|---|---|---|
| POST | `/register` | Tidak | Publik | Registrasi pengguna |
| POST | `/login` | Tidak | Publik | Login dan memperoleh JWT |
| POST | `/logout` | Ya | Pengguna login | Memvalidasi token dan memberikan respons logout |
| GET | `/mahasiswa` | Ya | Pengguna login | Mengambil seluruh data mahasiswa |
| GET | `/mahasiswa?id={id}` | Ya | Pengguna login | Mengambil satu data mahasiswa |
| POST | `/mahasiswa` | Ya | Admin | Menambahkan mahasiswa |
| PUT | `/mahasiswa?id={id}` | Ya | Admin | Memperbarui mahasiswa |
| POST | `/mahasiswa/update?id={id}` | Ya | Admin | Alternatif endpoint pembaruan |
| DELETE | `/mahasiswa?id={id}` | Ya | Admin | Menghapus mahasiswa |

## 4. Detail Endpoint

### 4.1 Registrasi Pengguna

**Request**

```http
POST /register
Content-Type: application/json
```

Body:

```json
{
  "username": "pengguna",
  "email": "pengguna@example.com",
  "password": "rahasia123"
}
```

Ketentuan:
- `username`, `email`, dan `password` wajib diisi.
- Email harus memiliki format yang valid.
- Password minimal 6 karakter.
- Akun baru otomatis diberikan role `user`.

Respons berhasil: HTTP `200`.

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": null
}
```

Kemungkinan error:
- `400`: data wajib diisi atau format email tidak valid atau password terlalu pendek.
- `409`: username/email sudah terdaftar atau data tidak valid.

### 4.2 Login

**Request**

```http
POST /login
Content-Type: application/json
```

Body:

```json
{
  "username": "admin",
  "password": "password"
}
```

Respons berhasil: HTTP `200`.

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "<jwt>",
    "role": "admin",
    "username": "admin"
  }
}
```

Token JWT memiliki masa berlaku 3600 detik atau satu jam.

Kemungkinan error:
- `400`: username atau password belum diisi.
- `401`: username atau password salah.

### 4.3 Logout

**Request**

```http
POST /logout
Authorization: Bearer <token>
```

Respons berhasil: HTTP `200`.

```json
{
  "success": true,
  "message": "Logout berhasil. Silakan hapus token di sisi frontend.",
  "data": null
}
```

Catatan: implementasi logout saat ini memvalidasi token dan meminta frontend menghapus token. Token tidak dimasukkan ke daftar pencabutan di server.

Kemungkinan error:
- `401`: token tidak ditemukan, tidak valid, atau kedaluwarsa.

### 4.4 Mengambil Seluruh Data Mahasiswa

**Request**

```http
GET /mahasiswa
Authorization: Bearer <token>
```

Respons berhasil: HTTP `200`.

```json
{
  "success": true,
  "message": "Data mahasiswa berhasil diambil",
  "data": {
    "total": 2,
    "mahasiswa": [
      {
        "id": 1,
        "nim": "E41250001",
        "nama": "Contoh Mahasiswa",
        "prodi": "Teknik Informatika",
        "foto": "foto_contoh.jpg"
      }
    ]
  }
}
```

Struktur objek mahasiswa mengikuti kolom yang dikembalikan model/database aplikasi.

Kemungkinan error:
- `401`: token tidak ditemukan, tidak valid, atau kedaluwarsa.
- `500`: gagal mengambil data mahasiswa.

### 4.5 Mengambil Satu Data Mahasiswa

**Request**

```http
GET /mahasiswa?id=1
Authorization: Bearer <token>
```

Respons berhasil: HTTP `200`.

```json
{
  "success": true,
  "message": "Data mahasiswa berhasil diambil",
  "data": {
    "id": 1,
    "nim": "E41250001",
    "nama": "Contoh Mahasiswa",
    "prodi": "Teknik Informatika",
    "foto": "foto_contoh.jpg"
  }
}
```

Kemungkinan error:
- `400`: ID mahasiswa tidak valid.
- `401`: token tidak ditemukan, tidak valid, atau kedaluwarsa.
- `404`: data mahasiswa tidak ditemukan.

### 4.6 Menambahkan Data Mahasiswa

**Request**

```http
POST /mahasiswa
Authorization: Bearer <token>
Content-Type: multipart/form-data
```

Field form:

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `nim` | Text | Ya | Nomor induk mahasiswa |
| `nama` | Text | Ya | Nama mahasiswa |
| `prodi` | Text | Ya | Program studi |
| `foto` | File | Tidak | Foto JPG/JPEG/PNG, maksimal 2 MB |

Endpoint ini hanya dapat digunakan oleh admin.

Respons berhasil: HTTP `201`.

```json
{
  "success": true,
  "message": "Data mahasiswa berhasil ditambahkan",
  "data": null
}
```

Kemungkinan error:
- `400`: NIM, nama, atau prodi wajib diisi.
- `401`: token tidak ditemukan, tidak valid, atau kedaluwarsa.
- `403`: pengguna bukan admin.
- `409`: NIM sudah terdaftar atau data tidak valid.
- `422`: upload gagal, ukuran melebihi 2 MB, format/isi file bukan gambar JPG/PNG yang valid.
- `500`: folder upload tidak tersedia, penyimpanan foto gagal, atau ekstensi EXIF tidak tersedia.

### 4.7 Memperbarui Data Mahasiswa

**Request utama**

```http
PUT /mahasiswa?id=1
Authorization: Bearer <token>
Content-Type: multipart/form-data
```

Field form:

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `nim` | Text | Ya | Nomor induk mahasiswa |
| `nama` | Text | Ya | Nama mahasiswa |
| `prodi` | Text | Ya | Program studi |
| `foto` | File | Tidak | Foto pengganti JPG/JPEG/PNG, maksimal 2 MB |

Foto bersifat opsional. Jika tidak dikirim, foto lama dipertahankan.

Alternatif endpoint yang juga terdaftar:

```http
POST /mahasiswa/update?id=1
Authorization: Bearer <token>
Content-Type: multipart/form-data
```

Respons berhasil: HTTP `200`.

```json
{
  "success": true,
  "message": "Data mahasiswa berhasil diperbarui",
  "data": null
}
```

Kemungkinan error:
- `400`: ID atau field wajib tidak valid.
- `401`: token tidak ditemukan, tidak valid, atau kedaluwarsa.
- `403`: pengguna bukan admin.
- `404`: data mahasiswa tidak ditemukan.
- `409`: NIM sudah digunakan atau data tidak valid.
- `422`: file foto tidak valid.

### 4.8 Menghapus Data Mahasiswa

**Request**

```http
DELETE /mahasiswa?id=1
Authorization: Bearer <token>
```

Endpoint ini hanya dapat digunakan oleh admin.

Respons berhasil: HTTP `200`.

```json
{
  "success": true,
  "message": "Data mahasiswa berhasil dihapus",
  "data": null
}
```

Kemungkinan error:
- `400`: ID mahasiswa tidak valid.
- `401`: token tidak ditemukan, tidak valid, atau kedaluwarsa.
- `403`: pengguna bukan admin.
- `404`: data mahasiswa tidak ditemukan.
- `500`: gagal menghapus data mahasiswa.

## 5. Kode Status HTTP

| Status | Arti |
|---|---|
| `200` | Permintaan berhasil |
| `201` | Data berhasil dibuat |
| `400` | Permintaan atau parameter tidak valid |
| `401` | Autentikasi gagal atau token tidak valid |
| `403` | Tidak memiliki hak akses |
| `404` | Endpoint atau data tidak ditemukan |
| `409` | Konflik data, misalnya username/NIM duplikat |
| `422` | Validasi file/upload gagal |
| `500` | Kesalahan internal server |

## 6. Alur Autentikasi dan Hak Akses

1. Pengguna mengirim username dan password ke `POST /login`.
2. Server memverifikasi kredensial dan menghasilkan JWT.
3. Frontend menyimpan token dan mengirimkannya pada header `Authorization: Bearer <token>` untuk endpoint terlindungi.
4. Server memvalidasi tanda tangan dan masa berlaku token.
5. Endpoint pengelolaan mahasiswa memerlukan role admin untuk operasi tambah, edit, dan hapus.
6. Registrasi publik menghasilkan akun dengan role `user`.

## 7. Catatan Keamanan Produksi

- Gunakan HTTPS untuk seluruh komunikasi API.
- Jangan menampilkan error PHP kepada pengunjung; aktifkan pencatatan error ke log.
- Simpan kredensial database dan JWT secret di luar direktori publik serta jangan commit ke Git.
- Gunakan JWT secret yang kuat dan jangan membagikannya.
- Batasi akses folder backend yang berada di luar `public_html` sebagai lapisan perlindungan tambahan.
- Logout pada implementasi saat ini menghapus token di sisi frontend; token yang sudah diterbitkan tetap berlaku sampai kedaluwarsa.
- CORS pada implementasi saat ini mengizinkan semua origin (`*`). Untuk produksi, pertimbangkan membatasi origin ke domain frontend yang benar-benar digunakan.

## 8. Informasi Implementasi

Dokumentasi ini disusun berdasarkan route pada `public/index.php`, mekanisme routing `app/core/Router.php`, controller autentikasi dan mahasiswa, serta kelas `Response` dan `Auth`.

Sebelum digunakan sebagai kontrak API final, contoh respons dan skema data mahasiswa perlu dicocokkan kembali dengan hasil pengujian langsung pada server produksi.
