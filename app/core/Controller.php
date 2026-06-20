<?php

class Controller
{
    /**
     * Method untuk memuat view
     * @param string $view Nama file view
     * @param array $data Data yang akan dikirim ke view
     */
    public function view($view, $data = [])
    {
        // Cek apakah file view ada
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die('View tidak ditemukan!');
        }
    }

    /**
     * Method untuk memuat model
     * @param string $model Nama file model
     */
    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        // Instansiasi model agar bisa dipakai
        return new $model();
    }

    protected function renderView($view, $data = [])
    {
        ob_start(); // Mulai output buffering
        require_once '../app/views/' . $view . '.php';
        $html = ob_get_contents(); // Ambil konten yang sudah di-buffer
        ob_end_clean(); // Hentikan dan bersihkan buffer
        return $html; // Kembalikan konten sebagai string
    }

    protected function logActivity($action, $description)
    {
        // Panggil model log
        $logModel = $this->model('LogActivity_model');

        // Nilai default jika tidak terdeteksi session login
        $user_id = null;
        $nama_user = 'Sistem / Guest';
        $role = 'guest';

        // 1. Cek jika yang login adalah Siswa
        if (isset($_SESSION['login_siswa']) && isset($_SESSION['data_siswa'])) {
            $user_id = $_SESSION['data_siswa']['id_induk'];
            $nama_user = $_SESSION['data_siswa']['nama_siswa'];
            $role = 'siswa';
        }
        // 2. Cek jika yang login adalah Guru / Admin (Sesuai Controller Guru Anda)
        elseif (isset($_SESSION['login_guru'])) {
            $user_id = $_SESSION['guru_id'] ?? null;
            $nama_user = $_SESSION['nama_guru'] ?? 'Guru';
            $role = 'guru/admin'; // Anda bisa mengubahnya dinamis jika ada session level khusus
        }

        $data = [
            'user_id'     => $user_id,
            'nama_user'   => $nama_user,
            'role'        => $role,
            'action'      => $action,
            'description' => $description
        ];

        // Eksekusi insert log
        return $logModel->insertLog($data);
    }
}
