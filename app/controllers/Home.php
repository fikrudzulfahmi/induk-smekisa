<?php

class Home extends Controller
{
    public function index()
    {
        // Cek apakah pengguna sudah login
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            // Jika sudah login, arahkan ke dashboard
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        } else {
            // Jika belum login, tampilkan landing page publik
            header('Location: ' . BASEURL . '/landing');
            exit;
        }
    }
}
