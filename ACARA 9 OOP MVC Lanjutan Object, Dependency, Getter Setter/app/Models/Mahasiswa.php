
<?php

class Mahasiswa
{
    private ?int $id = null;
    private string $nim;
    private string $nama;
    private string $email;
    private int $prodi_id;
    private int $angkatan;

    public function __construct(
        string $nim,
        string $nama,
        string $email,
        int $prodi_id,
        int $angkatan,
        ?int $id = null
    ) {
        $this->setNim($nim);
        $this->setNama($nama);
        $this->setEmail($email);
        $this->setProdiId($prodi_id);
        $this->setAngkatan($angkatan);
        $this->id = $id;
    }

    // Getter
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getProdiId(): int
    {
        return $this->prodi_id;
    }

    public function getAngkatan(): int
    {
        return $this->angkatan;
    }

    // Setter
    public function setNim(string $nim): void
    {
        $nim = trim($nim);

        if ($nim === '' || !ctype_digit($nim)) {
            throw new InvalidArgumentException(
                'NIM harus diisi dan hanya boleh berisi angka.'
            );
        }

        $this->nim = $nim;
    }

    public function setNama(string $nama): void
    {
        $nama = trim($nama);

        if ($nama === '') {
            throw new InvalidArgumentException(
                'Nama mahasiswa tidak boleh kosong.'
            );
        }

        $this->nama = $nama;
    }

    public function setEmail(string $email): void
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                'Format email tidak valid.'
            );
        }

        $this->email = $email;
    }

    public function setProdiId(int $prodi_id): void
    {
        if ($prodi_id <= 0) {
            throw new InvalidArgumentException(
                'Program studi harus dipilih.'
            );
        }

        $this->prodi_id = $prodi_id;
    }

    public function setAngkatan(int $angkatan): void
    {
        if ($angkatan < 2000 || $angkatan > 2100) {
            throw new InvalidArgumentException(
                'Tahun angkatan tidak valid.'
            );
        }

        $this->angkatan = $angkatan;
    }

    public function setId(?int $id): void
    {
        if ($id !== null && $id <= 0) {
            throw new InvalidArgumentException(
                'ID tidak valid.'
            );
        }

        $this->id = $id;
    }
}