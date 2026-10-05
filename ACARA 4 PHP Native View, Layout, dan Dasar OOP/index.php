
<?php

require_once __DIR__ . '/app/models/mahasiswa.php';

// Membuat object mahasiswa
$mahasiswa = [
    new Mahasiswa(
        "2601001",
        "Mochammad Syifaul Qulub",
        "Teknik Informatika"
    ),

    new Mahasiswa(
        "2501002",
        "Ahmad Fauzi",
        "Teknik Informatika"
    ),

    new Mahasiswa(
        "2401003",
        "Siti Aminah",
        "Manajemen Agribisnis"
    ),

    new Mahasiswa(
        "2601004",
        "Budi Santoso",
        "Teknik Informatika"
    )
];

// Menyiapkan data untuk layout
$title = "Data Mahasiswa";
$content = __DIR__ . '/app/views/mahasiswa/index.php';

// Memanggil layout utama
require __DIR__ . '/app/views/layouts/main.php';