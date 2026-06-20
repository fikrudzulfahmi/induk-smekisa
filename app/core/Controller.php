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
}
