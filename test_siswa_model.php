<?php
// File test untuk debugging - bisa dihapus setelah selesai
session_start();

require_once './app/core/Database.php';
require_once './app/models/Siswa_model.php';

$model = new Siswa_model();

// Test ambil data siswa
$id = $_SESSION['data_siswa']['id_induk'] ?? 1; // Default ke ID 1 untuk testing

echo "<h2>Test Query Siswa Model</h2>";
echo "<p>ID yang dicari: $id</p>";

$result = $model->getDetailSiswaById($id);

echo "<h3>Hasil Query:</h3>";
if ($result) {
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    echo "<p style='color: green;'>✓ Data ditemukan</p>";
} else {
    echo "<p style='color: red;'>✗ Data tidak ditemukan (NULL)</p>";
}
