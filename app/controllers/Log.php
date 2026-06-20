<?php

class Log extends Controller
{
    // Tampilan Utama Halaman Log di Admin
    public function index()
    {
        // Proteksi: Pastikan hanya user admin/guru yang sudah login yang bisa masuk
        if (!isset($_SESSION['login_guru'])) {
            header('Location: ' . BASEURL . '/guru/login');
            exit;
        }

        $data['judul'] = 'Activity Log Sistem';

        // Memanggil template Mazer Admin Anda
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('log/index', $data); // Mengarah ke file view log yang kita buat kemarin
        $this->view('templates/footer');
    }

    // Fitur Penyedia Data AJAX untuk DataTables Server-Side (Mirip getServerSideGuru)
    public function getServerSideLog()
    {
        if (!isset($_SESSION['login_guru'])) {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $requestData = $_POST;
        // Panggil model log dan jalankan fungsi server-side
        $output = $this->model('LogActivity_model')->getDataLogServerSide($requestData);
        echo json_encode($output);
    }
}
