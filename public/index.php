<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL);

// Paksa PHP untuk menampilkannya ke layar browser
ini_set('display_errors', 1);
// Menentukan path untuk menyimpan session
$sessionPath = '../app/sessions';
// Cek jika folder belum ada, maka buat folder tersebut
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}
// Set path penyimpanan session
session_save_path($sessionPath);

session_start();
// Bootstraping atau memanggil file utama aplikasi
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';

// Tambahkan dua baris ini
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

// DAFTARKAN HELPER DI SINI
require_once '../app/helpers/TokenHelper.php';
require_once '../app/helpers/Flasher.php';

require_once '../app/helpers/Auth.php'; // <-- TAMBAHKAN BARIS INI
require_once '../app/helpers/Tanggalindo.php';
require_once '../app/helpers/Formatpenghasilan.php';
require_once '../app/vendor/autoload.php';
// Inisialisasi kelas App
$app = new App();
