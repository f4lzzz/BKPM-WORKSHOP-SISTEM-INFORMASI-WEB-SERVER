<?php

require_once __DIR__ . '/../Models/MahasiswaRepository.php';

class MahasiswaService
{
    private MahasiswaRepository $repository;

    public function __construct()
    {
        $this->repository = new MahasiswaRepository();
    }

    public function getAll(): array
    {
        return $this->repository->all();
    }

    public function getById(int $id): ?array
    {
        return $this->repository->find($id);
    }

    public function create(array $input): int
    {
        $data = $this->validate($input);

        if ($this->repository->existsByNim($data['nim'])) {
            throw new Exception('NIM sudah terdaftar.');
        }

        $mahasiswa = new Mahasiswa(
            $data['nim'],
            $data['nama'],
            $data['email'],
            $data['angkatan']
        );

        return $this->repository->create($mahasiswa);
    }

    public function update(int $id, array $input): bool
    {
        $data = $this->validate($input);

        $mahasiswa = $this->repository->find($id);

        if (!$mahasiswa) {
            throw new Exception('Data mahasiswa tidak ditemukan.');
        }

        if ($this->repository->existsByNim($data['nim'], $id)) {
            throw new Exception('NIM sudah terdaftar.');
        }

        $entity = new Mahasiswa(
            $data['nim'],
            $data['nama'],
            $data['email'],
            $data['angkatan'],
            $id
        );

        return $this->repository->update($entity);
    }

    public function delete(int $id): bool
    {
        $mahasiswa = $this->repository->find($id);

        if (!$mahasiswa) {
            throw new Exception('Data mahasiswa tidak ditemukan.');
        }

        return $this->repository->delete($id);
    }

    private function validate(array $input): array
    {
        $nim = trim($input['nim'] ?? '');
        $nama = trim($input['nama'] ?? '');
        $email = trim($input['email'] ?? '');
        $angkatan = trim($input['angkatan'] ?? '');

        if ($nim === '') {
            throw new Exception('NIM wajib diisi.');
        }

        if (!ctype_digit($nim)) {
            throw new Exception('NIM harus berupa angka.');
        }

        if (strlen($nim) < 8 || strlen($nim) > 20) {
            throw new Exception('NIM harus memiliki 8 sampai 20 karakter.');
        }

        if ($nama === '') {
            throw new Exception('Nama wajib diisi.');
        }

        if (strlen($nama) < 3) {
            throw new Exception('Nama minimal 3 karakter.');
        }

        if ($email === '') {
            throw new Exception('Email wajib diisi.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format email tidak valid.');
        }

        if ($angkatan === '') {
            throw new Exception('Angkatan wajib diisi.');
        }

        if (!ctype_digit($angkatan)) {
            throw new Exception('Angkatan harus berupa angka.');
        }

        $angkatan = (int) $angkatan;

        if ($angkatan < 2000 || $angkatan > 2100) {
            throw new Exception('Angkatan harus berada antara 2000 sampai 2100.');
        }

        return [
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'angkatan' => $angkatan
        ];
    }
}