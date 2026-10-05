
<?php

class Mahasiswa
{
    private ?int $id;
    private string $nim;
    private string $nama;
    private string $email;
    private int $prodi_id;
    private int $angkatan;

    public function __construct(
        string $nim = '',
        string $nama = '',
        string $email = '',
        int $prodi_id = 0,
        int $angkatan = 0,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->setNim($nim);
        $this->setNama($nama);
        $this->setEmail($email);
        $this->setProdiId($prodi_id);
        $this->setAngkatan($angkatan);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        if ($id !== null && $id <= 0) {
            throw new InvalidArgumentException('ID tidak valid.');
        }

        $this->id = $id;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function setNim(string $nim): void
    {
        if (trim($nim) === '') {
            throw new InvalidArgumentException('NIM wajib diisi.');
        }

        if (!ctype_digit($nim)) {
            throw new InvalidArgumentException('NIM harus berupa angka.');
        }

        $this->nim = $nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        if (trim($nama) === '') {
            throw new InvalidArgumentException('Nama wajib diisi.');
        }

        $this->nama = $nama;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email tidak valid.');
        }

        $this->email = $email;
    }

    public function getProdiId(): int
    {
        return $this->prodi_id;
    }

    public function setProdiId(int $prodi_id): void
    {
        if ($prodi_id <= 0) {
            throw new InvalidArgumentException('Program studi wajib dipilih.');
        }

        $this->prodi_id = $prodi_id;
    }

    public function getAngkatan(): int
    {
        return $this->angkatan;
    }

    public function setAngkatan(int $angkatan): void
    {
        if ($angkatan < 2000 || $angkatan > 2100) {
            throw new InvalidArgumentException('Angkatan tidak valid.');
        }

        $this->angkatan = $angkatan;
    }
}