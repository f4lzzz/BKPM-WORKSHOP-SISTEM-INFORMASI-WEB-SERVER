# DOKUMENTASI MANUAL TESTING DAN BLACKBOX TESTING
## Acara 23 – Workshop Sistem Informasi Web Server

### 1. Informasi Pengujian

- Pokok Bahasan: Manual Testing dan Blackbox Testing
- Minggu: 12 / Pertemuan 1
- Penguji: Mochammad Syifaul Qulub
- Sistem: RESTful API Mahasiswa
- Metode: Manual Testing – Blackbox Testing
- Tools: PHP, MySQL, Laragon, PowerShell

### 2. Tujuan Pengujian

Pengujian dilakukan untuk memastikan setiap endpoint REST API berjalan sesuai kebutuhan, memvalidasi input, menangani kesalahan, serta menerapkan autentikasi token pada endpoint yang dilindungi.

### 3. Hasil Pengujian

| ID | Skenario Pengujian | Data Uji | Expected Result | Actual Result | Status |
|---|---|---|---|---|---|
| TC01 | Menambahkan mahasiswa dengan data lengkap | NIM valid dan seluruh field terisi | 201 Created | Data berhasil ditambahkan | PASS |
| TC02 | Menambahkan mahasiswa dengan NIM kosong | NIM kosong | 400 Bad Request | NIM, nama, dan prodi wajib diisi | PASS |
| TC03 | Menambahkan mahasiswa dengan NIM duplikat | TEST230001 | 409 Conflict | NIM sudah terdaftar | PASS |
| TC04 | Login dengan username dan password benar | admin23 dan password valid | 200 OK dan token tersedia | Login berhasil dan token diperoleh | PASS |
| TC05 | Login dengan password salah | Password salah | 401 Unauthorized | Username atau password salah | PASS |
| TC06 | Menambahkan mahasiswa tanpa token | Request tanpa Authorization | 401 Unauthorized | Token tidak ditemukan | PASS |
| TC07 | Update mahasiswa dengan ID valid | ID 1 dan data lengkap | 200 OK | Data mahasiswa berhasil diperbarui | PASS |
| TC08 | Update mahasiswa dengan ID tidak ditemukan | ID 9999 | 404 Not Found | Data mahasiswa tidak ditemukan | PASS |
| TC09 | Update mahasiswa dengan field kosong | Nama kosong | 400 Bad Request | NIM, nama, dan prodi wajib diisi | PASS |
| TC10 | Update menggunakan NIM milik mahasiswa lain | NIM 260230002 | 409 Conflict | NIM sudah digunakan mahasiswa lain | PASS |
| TC11 | Delete mahasiswa dengan ID valid | ID 4 | 200 OK | Data mahasiswa berhasil dihapus | PASS |
| TC12 | Delete mahasiswa dengan ID tidak ditemukan | ID 9999 | 404 Not Found | Data mahasiswa tidak ditemukan | PASS |
| TC13 | Delete tanpa token | Request tanpa Authorization | 401 Unauthorized | Token tidak ditemukan | PASS |

### 4. Analisis Hasil Pengujian

Berdasarkan pengujian yang telah dilakukan, seluruh skenario pengujian memperoleh hasil sesuai dengan respons yang diharapkan.

Validasi input berhasil menolak data yang tidak lengkap. Sistem juga mampu menangani NIM duplikat, memeriksa keberadaan data berdasarkan ID, serta menolak akses ke endpoint yang membutuhkan autentikasi apabila token tidak disertakan.

Pengujian UPDATE dan DELETE menunjukkan bahwa sistem dapat memperbarui maupun menghapus data sesuai ID yang tersedia.

### 5. Kesimpulan

Berdasarkan hasil manual testing dan blackbox testing, REST API Mahasiswa telah berjalan sesuai dengan skenario pengujian yang dirancang. Seluruh 13 test case memperoleh status PASS.

Pengujian ini menunjukkan bahwa fungsi registrasi, login, pengambilan data, penambahan, pembaruan, dan penghapusan mahasiswa telah memenuhi hasil yang diharapkan.
