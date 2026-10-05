<?php

require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';

class MahasiswaController extends BaseController
{
    private MahasiswaService $service;

    public function __construct()
    {
        $this->service = new MahasiswaService();
    }

    public function index(): void
    {
        try {
            $mahasiswa = $this->service->getAll();

            $this->view('mahasiswa/index', [
                'mahasiswa' => $mahasiswa
            ]);
        } catch (Exception $e) {
            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data mahasiswa gagal dimuat.'
            ];

            $this->redirect('/mahasiswa');
        }
    }

    public function create(): void
    {
        $this->view('mahasiswa/create');
    }

    public function store(): void
    {
        try {
            $this->service->create($_POST);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Data mahasiswa berhasil ditambahkan.'
            ];

            // PRG: POST → Redirect → GET
            $this->redirect('/mahasiswa');
        } catch (Exception $e) {
            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => $this->getSafeMessage($e)
            ];

            $this->redirect('/mahasiswa/create');
        }
    }

    public function edit(int $id): void
    {
        try {
            $mahasiswa = $this->service->getById($id);

            if (!$mahasiswa) {
                throw new Exception('Data mahasiswa tidak ditemukan.');
            }

            $this->view('mahasiswa/edit', [
                'mahasiswa' => $mahasiswa
            ]);
        } catch (Exception $e) {
            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data mahasiswa tidak ditemukan.'
            ];

            $this->redirect('/mahasiswa');
        }
    }

    public function update(int $id): void
    {
        try {
            $this->service->update($id, $_POST);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Data mahasiswa berhasil diubah.'
            ];

            // PRG: POST → Redirect → GET
            $this->redirect('/mahasiswa');
        } catch (Exception $e) {
            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => $this->getSafeMessage($e)
            ];

            $this->redirect('/mahasiswa/edit?id=' . $id);
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->service->delete($id);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Data mahasiswa berhasil dihapus.'
            ];

            // PRG: POST → Redirect → GET
            $this->redirect('/mahasiswa');
        } catch (Exception $e) {
            $this->logError($e);

            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Data mahasiswa gagal dihapus.'
            ];

            $this->redirect('/mahasiswa');
        }
    }

    private function logError(Exception $e): void
    {
        $logDirectory = __DIR__ . '/../../storage/logs';

        if (!is_dir($logDirectory)) {
            mkdir($logDirectory, 0777, true);
        }

        $logFile = $logDirectory . '/app.log';

        $message =
            date('Y-m-d H:i:s') .
            ' - ' .
            $e->getMessage() .
            PHP_EOL;

        error_log($message, 3, $logFile);
    }

    private function getSafeMessage(Exception $e): string
    {
        $message = $e->getMessage();

        $safeMessages = [
            'NIM wajib diisi.',
            'NIM harus berupa angka.',
            'NIM harus memiliki 8 sampai 20 karakter.',
            'NIM sudah terdaftar.',
            'Nama wajib diisi.',
            'Nama minimal 3 karakter.',
            'Email wajib diisi.',
            'Format email tidak valid.',
            'Angkatan wajib diisi.',
            'Angkatan harus berupa angka.',
            'Angkatan harus berada antara 2000 sampai 2100.',
            'Data mahasiswa tidak ditemukan.'
        ];

        if (in_array($message, $safeMessages, true)) {
            return $message;
        }

        return 'Terjadi kesalahan pada aplikasi. Silakan coba lagi.';
    }
}