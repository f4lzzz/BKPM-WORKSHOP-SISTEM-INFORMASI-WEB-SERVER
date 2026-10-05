
<?php

class Mahasiswa
{
    private $nim;
    private $nama;
    private $prodi;

    public function __construct($nim, $nama, $prodi)
    {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->prodi = $prodi;
    }

    // Getter NIM
    public function getNim()
    {
        return $this->nim;
    }

    // Getter Nama
    public function getNama()
    {
        return $this->nama;
    }

    // Getter Program Studi
    public function getProdi()
    {
        return $this->prodi;
    }

    // Mengambil angkatan dari 2 digit awal NIM
    public function getAngkatan()
    {
        $duaDigitAwal = substr($this->nim, 0, 2);

        return "20" . $duaDigitAwal;
    }
}