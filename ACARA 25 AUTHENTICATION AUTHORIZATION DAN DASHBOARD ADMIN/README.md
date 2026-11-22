# Acara 25 — Authentication, Authorization, dan Dashboard Admin

## Deskripsi
Project ini merupakan implementasi sistem Authentication dan Authorization menggunakan PHP Native, MySQL, RESTful API, serta JavaScript Fetch API.

Studi kasus yang digunakan adalah sistem pengelolaan data mahasiswa dengan dua hak akses, yaitu admin dan user.

## Teknologi
- PHP Native
- MySQL
- HTML
- CSS
- JavaScript
- Fetch API
- JSON Web Token (JWT)
- Laragon

## Fitur
1. Registrasi akun.
2. Login menggunakan username dan password.
3. Autentikasi menggunakan JWT.
4. Pembagian hak akses berdasarkan role.
5. Dashboard mahasiswa.
6. Menampilkan total mahasiswa.
7. CRUD data mahasiswa khusus admin.
8. User hanya dapat melihat data mahasiswa.
9. Logout dan penghapusan token pada frontend.
10. Proteksi endpoint API menggunakan middleware authorization.

## Hak Akses

| Fitur | Admin | User |
|---|---|---|
| Login | Ya | Ya |
| Melihat data mahasiswa | Ya | Ya |
| Menambah mahasiswa | Ya | Tidak |
| Mengubah mahasiswa | Ya | Tidak |
| Menghapus mahasiswa | Ya | Tidak |
| Melihat total mahasiswa | Ya | Ya |

## Struktur Database

### Tabel users
- id
- username
- email
- password
- role
- created_at

### Tabel mahasiswa
- id
- nim
- nama
- prodi
- created_at

## Endpoint API

| Method | Endpoint | Keterangan |
|---|---|---|
| POST | /register | Registrasi akun |
| POST | /login | Login pengguna |
| POST | /logout | Logout pengguna |
| GET | /mahasiswa | Mengambil data mahasiswa |
| POST | /mahasiswa | Menambahkan mahasiswa (admin) |
| PUT | /mahasiswa?id={id} | Mengubah mahasiswa (admin) |
| DELETE | /mahasiswa?id={id} | Menghapus mahasiswa (admin) |

## Hasil Pengujian
1. Registrasi akun user dan admin berhasil.
2. Login sebagai user berhasil.
3. Login sebagai admin berhasil.
4. Dashboard menampilkan total dan data mahasiswa.
5. Admin berhasil melakukan Create, Read, Update, dan Delete.
6. User tidak dapat melakukan CRUD melalui API.
7. API memberikan pesan penolakan akses: "Akses ditolak, hanya admin yang diizinkan".
8. Logout berhasil mengarahkan pengguna kembali ke halaman login.

## Cara Menjalankan
1. Jalankan Apache dan MySQL melalui Laragon.
2. Import database `acara25.sql` melalui phpMyAdmin.
3. Pastikan konfigurasi database sesuai dengan pengaturan lokal.
4. Buka halaman login melalui browser menggunakan folder project di localhost.
5. Login menggunakan akun yang sudah terdaftar.

## Kesimpulan
Project ini berhasil menerapkan Authentication dan Authorization berbasis role. Hak akses admin dan user dibedakan, serta pembatasan CRUD diterapkan pada backend sehingga endpoint API tetap terlindungi meskipun diakses secara langsung.