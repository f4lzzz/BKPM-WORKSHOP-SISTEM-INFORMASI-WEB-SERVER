# ACARA 26 - Manajemen Konten, Upload File dan Database Management

**Mata Kuliah:** Workshop Sistem Informasi Web Server  
**Program Studi:** Teknik Informatika  
**Institusi:** Politeknik Negeri Jember  
**Studi Kasus:** Sistem Manajemen Data Mahasiswa

## 1. Deskripsi Proyek

Proyek ini merupakan pengembangan RESTful API menggunakan PHP Native dan MySQL untuk mengelola data mahasiswa dengan fitur upload foto, validasi file, pengelolaan file gambar, serta backup dan restore database.

Sistem memiliki dua hak akses, yaitu admin dan user. Admin dapat melakukan pengelolaan data mahasiswa, sedangkan user hanya dapat melihat data mahasiswa.

## 2. Teknologi yang Digunakan

- PHP Native
- MySQL 8.0
- HTML, CSS, dan JavaScript
- PDO (PHP Data Objects)
- Laragon
- PowerShell
- JSON untuk format response API

## 3. Struktur Folder

```text
ACARA 26 Manajemen Konten Upload File dan Database Management/
│
├── app/
│   ├── config/
│   │   └── Database.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   └── MahasiswaController.php
│   ├── core/
│   │   ├── Auth.php
│   │   ├── Response.php
│   │   └── Router.php
│   └── models/
│       ├── Mahasiswa.php
│       └── User.php
│
├── database/
│   ├── backup/
│   │   └── acara26_backup.sql
│   ├── acara25.sql
│   └── JADWAL_BACKUP.md
│
└── public/
    ├── uploads/
    ├── views/
    │   ├── dashboard.html
    │   ├── dashboard.js
    │   ├── login.html
    │   └── login.js
    ├── .htaccess
    └── index.php
```

## 4. Fitur Sistem

### Authentication dan Authorization
- Login menggunakan JWT.
- Hak akses admin dan user.
- Pembatasan akses berdasarkan role.

### Manajemen Data Mahasiswa
- Menampilkan seluruh data mahasiswa.
- Menambahkan data mahasiswa.
- Mengubah data mahasiswa.
- Menghapus data mahasiswa.
- Menghitung jumlah mahasiswa.

### Upload dan Pengelolaan Foto
- Upload foto mahasiswa dengan format JPG dan PNG.
- Validasi tipe file menggunakan `exif_imagetype()`.
- Pembatasan ukuran file maksimal 2 MB.
- Penyimpanan file menggunakan `move_uploaded_file()`.
- Pembuatan nama file unik.
- Penghapusan foto lama menggunakan `unlink()` ketika foto diganti.
- Penghapusan foto ketika data mahasiswa dihapus.

### Database Management
- Backup database menggunakan `mysqldump`.
- Restore database menggunakan MySQL client.
- Verifikasi hasil restore dengan membandingkan jumlah data.

## 5. Endpoint API

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/mahasiswa` | Menampilkan data mahasiswa |
| POST | `/mahasiswa` | Menambahkan mahasiswa |
| POST | `/mahasiswa/update?id={id}` | Mengubah data mahasiswa |
| PUT | `/mahasiswa?id={id}` | Mengubah data mahasiswa |
| DELETE | `/mahasiswa?id={id}` | Menghapus mahasiswa |

Endpoint penambahan, perubahan, dan penghapusan data hanya dapat diakses oleh admin.

## 6. Konfigurasi Database

Nama database:

`acara 26`

Tabel yang digunakan:
- `users`
- `mahasiswa`

Tabel mahasiswa memiliki kolom:
- `id`
- `nim`
- `nama`
- `prodi`
- `foto`
- `created_at`

## 7. Backup dan Restore Database

Backup database dilakukan menggunakan PowerShell:

```powershell
& "C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe" -u root "acara 26" --result-file="database\backup\acara26_backup.sql"
```

Restore dilakukan dengan mengimpor file SQL ke database tujuan menggunakan MySQL client.

Backup disimpan di:

`database/backup/acara26_backup.sql`

Jadwal backup dilaksanakan setiap akhir sesi praktikum.

## 8. Pengujian

Pengujian yang telah dilakukan:

1. Login sebagai admin dan user.
2. Pengujian hak akses admin dan user.
3. Menambahkan data mahasiswa dengan foto.
4. Validasi format file yang tidak didukung.
5. Validasi ukuran foto melebihi 2 MB.
6. Mengubah foto mahasiswa.
7. Memastikan foto lama terhapus setelah diganti.
8. Menghapus data mahasiswa beserta fotonya.
9. Melakukan backup database.
10. Melakukan restore database ke database sementara.
11. Membandingkan jumlah data mahasiswa sebelum dan sesudah restore.

Hasil pengujian menunjukkan fitur-fitur tersebut berjalan sesuai dengan tujuan praktikum.

## 9. Cara Menjalankan Proyek

1. Jalankan Laragon.
2. Aktifkan Apache dan MySQL.
3. Pastikan database `acara 26` tersedia.
4. Periksa konfigurasi koneksi pada `app/config/Database.php`.
5. Buka halaman login melalui browser:

```text
http://localhost/BKPM%20WORKSHOP%20SISTEM%20INFORMASI%20WEB%20SERVER/ACARA%2026%20Manajemen%20Konten%20Upload%20File%20dan%20Database%20Management/public/
```

6. Login menggunakan akun admin atau user yang tersedia.

---

**Praktikum Acara 26 - Workshop Sistem Informasi Web Server**  
**Politeknik Negeri Jember**