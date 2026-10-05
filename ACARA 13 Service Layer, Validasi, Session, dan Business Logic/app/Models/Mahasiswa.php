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
        ?int $id,
        string $nim,
        string $nama,
        string $email,
        int $prodi_id,
        int $angkatan
    ) {
        $this->id = $id;
        $this->nim = $nim;
        $this->nama = $nama;
        $this->email = $email;
        $this->prodi_id = $prodi_id;
        $this->angkatan = $angkatan;
    }

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

    public function setNim(string $nim): void
    {
        $this->nim = $nim;
    }

    public function setNama(string $nama): void
    {
        $this->nama = $nama;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setProdiId(int $prodi_id): void
    {
        $this->prodi_id = $prodi_id;
    }

    public function setAngkatan(int $angkatan): void
    {
        $this->angkatan = $angkatan;
    }
}