<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';
require_once __DIR__ . '/../Models/MahasiswaRepository.php';
require_once __DIR__ . '/../Models/ProdiRepository.php';

class MahasiswaService
{
    private MahasiswaRepository $mahasiswaRepository;
    private ProdiRepository $prodiRepository;

    public function __construct(
        MahasiswaRepository $mahasiswaRepository,
        ProdiRepository $prodiRepository
    ) {
        $this->mahasiswaRepository = $mahasiswaRepository;
        $this->prodiRepository = $prodiRepository;
    }

    public function getAll(): array
    {
        return $this->mahasiswaRepository->getAll();
    }

    public function getById(int $id): ?array
    {
        return $this->mahasiswaRepository->find($id);
    }

    public function getProdi(): array
    {
        return $this->prodiRepository->getAll();
    }

    public function create(array $data): array
    {
        $errors = $this->validate($data);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        if ($this->mahasiswaRepository->findByNim($data['nim'])) {
            return [
                'success' => false,
                'errors' => [
                    'nim' => 'NIM sudah digunakan.'
                ]
            ];
        }

        $mahasiswa = new Mahasiswa(
            null,
            $data['nim'],
            $data['nama'],
            $data['email'],
            (int) $data['prodi_id'],
            (int) $data['angkatan']
        );

        try {
            $success = $this->mahasiswaRepository->create($mahasiswa);

            if ($success) {
                return [
                    'success' => true,
                    'message' => 'Data mahasiswa berhasil ditambahkan.'
                ];
            }

            return [
                'success' => false,
                'errors' => [
                    'general' => 'Data mahasiswa gagal disimpan.'
                ]
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'errors' => [
                    'general' => 'Data mahasiswa gagal disimpan.'
                ]
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        $errors = $this->validate($data);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        if ($this->mahasiswaRepository->findByNim($data['nim'], $id)) {
            return [
                'success' => false,
                'errors' => [
                    'nim' => 'NIM sudah digunakan oleh mahasiswa lain.'
                ]
            ];
        }

        $existing = $this->mahasiswaRepository->find($id);

        if (!$existing) {
            return [
                'success' => false,
                'errors' => [
                    'general' => 'Data mahasiswa tidak ditemukan.'
                ]
            ];
        }

        $mahasiswa = new Mahasiswa(
            $id,
            $data['nim'],
            $data['nama'],
            $data['email'],
            (int) $data['prodi_id'],
            (int) $data['angkatan']
        );

        try {
            $success = $this->mahasiswaRepository->update($mahasiswa);

            if ($success) {
                return [
                    'success' => true,
                    'message' => 'Data mahasiswa berhasil diperbarui.'
                ];
            }

            return [
                'success' => false,
                'errors' => [
                    'general' => 'Data mahasiswa gagal diperbarui.'
                ]
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'errors' => [
                    'general' => 'Data mahasiswa gagal diperbarui.'
                ]
            ];
        }
    }

    public function delete(int $id): bool
    {
        return $this->mahasiswaRepository->delete($id);
    }

    private function validate(array $data): array
    {
        $errors = [];

        $nim = trim($data['nim'] ?? '');
        $nama = trim($data['nama'] ?? '');
        $email = trim($data['email'] ?? '');
        $prodiId = (int) ($data['prodi_id'] ?? 0);
        $angkatan = (int) ($data['angkatan'] ?? 0);

        if ($nim === '') {
            $errors['nim'] = 'NIM wajib diisi.';
        } elseif (!preg_match('/^[0-9]+$/', $nim)) {
            $errors['nim'] = 'NIM hanya boleh berisi angka.';
        } elseif (strlen($nim) < 8 || strlen($nim) > 20) {
            $errors['nim'] = 'NIM harus terdiri dari 8 sampai 20 karakter.';
        }

        if ($nama === '') {
            $errors['nama'] = 'Nama wajib diisi.';
        } elseif (strlen($nama) < 3) {
            $errors['nama'] = 'Nama minimal 3 karakter.';
        }

        if ($email === '') {
            $errors['email'] = 'Email wajib diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        }

        if ($prodiId <= 0 || !$this->prodiRepository->find($prodiId)) {
            $errors['prodi_id'] = 'Program studi tidak valid.';
        }

        if ($angkatan < 2000 || $angkatan > 2100) {
            $errors['angkatan'] = 'Tahun angkatan tidak valid.';
        }

        return $errors;
    }
} 