\# JADWAL BACKUP DATABASE



\## Proyek

Acara 26 - Manajemen Konten, Upload File dan Database Management



\## Database

`acara 26`



\## Jadwal Backup



Backup database dilakukan setiap akhir sesi praktikum untuk menjaga keamanan dan ketersediaan data.



| Kegiatan | Jadwal |

|---|---|

| Backup database | Setiap akhir sesi praktikum |

| Penyimpanan backup | Folder `database/backup/` |

| Format file | `.sql` |

| Restore database | Ketika terjadi kehilangan atau kerusakan data |

| Verifikasi backup | Setelah proses restore |



\## Pelaksanaan Backup



Backup dilakukan menggunakan perintah `mysqldump` melalui PowerShell. File hasil backup disimpan dengan nama `acara26\_backup.sql`.



\## Pengujian Restore



File backup telah berhasil diimpor ke database sementara `acara 26 restore test`.



Hasil verifikasi:

\- Database utama: 3 data mahasiswa.

\- Database hasil restore: 3 data mahasiswa.

\- Status: Berhasil.



\## Kesimpulan



Backup dan restore database berhasil dilakukan. Backup dijadwalkan setiap akhir sesi praktikum untuk mengurangi risiko kehilangan data.

