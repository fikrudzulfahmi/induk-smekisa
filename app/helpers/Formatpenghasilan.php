<?php

/**
 * Memformat angka menjadi format Rupiah Indonesia (Rp xxx.xxx,-).
 * * @param mixed $nilai Nilai angka atau string yang akan diformat.
 * @param string $default Nilai default jika input tidak valid. Default '-'.
 * @return string String yang sudah diformat atau nilai default.
 */
function formatPenghasilan($nilai, $default = '-')
{
    // Cek jika null, kosong, atau hanya strip
    if (is_null($nilai) || trim((string)$nilai) === '' || trim((string)$nilai) === '-') {
        return $default;
    }

    // Hapus semua karakter non-digit kecuali tanda minus di depan (jika ada)
    $angka_str = preg_replace('/[^\d-]/', '', (string)$nilai);

    // Cek lagi jika setelah dibersihkan menjadi kosong atau hanya minus
    if ($angka_str === '' || $angka_str === '-') {
        return $default;
    }

    // Cek jika nilai valid secara numerik
    if (!is_numeric($angka_str)) {
        return $default; // Kembalikan default jika tidak numerik
    }

    // Konversi ke integer atau float jika perlu (tapi formatnya tanpa desimal)
    $angka = (int)$angka_str;

    // Format menggunakan number_format() untuk Indonesia
    // 0 desimal, ',' pemisah desimal, '.' pemisah ribuan
    $format_rupiah = number_format($angka, 0, ',', '.');

    // Tambahkan "Rp " di depan dan ",-" di belakang
    return 'Rp ' . $format_rupiah; // Menghilangkan ,- agar lebih bersih jika 0
    // Jika ingin tetap ada ,- : return 'Rp ' . $format_rupiah . ',-';
}
