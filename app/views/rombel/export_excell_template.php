<?php
// Ambil data dari controller
$rombel = $data['rombel'];
$siswa_list = $data['siswa_list'];
$tp = $data['tp'];
$nama_rombel = $rombel->nama_rombel ?? 'Kelas Tidak Diketahui';
$nama_walas = $rombel->nama_guru ?? '-'; // Nama guru dari join

// Output header HTML (meta charset penting)
echo '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>';
echo '<table border="1" cellpadding="4" cellspacing="0" style="border-collapse:collapse; font-family:Arial; font-size:11px; width:100%;">'; // Font size lebih kecil

// Header Laporan
echo '<tr><th colspan="8" style="border:none; text-align:center; font-size:14px; font-weight:bold;">DAFTAR ALAMAT SISWA TAHUN PELAJARAN ' . htmlspecialchars($tp) . '</th></tr>';
echo '<tr><th colspan="8" style="border:none; text-align:center; font-size:12px; font-weight:bold;">SEKOLAH MENENGAH KEJURUAN ISLAM 1 BLITAR</th></tr>'; // Sesuaikan Nama Sekolah
echo '<tr><th colspan="8" style="border:none; text-align:left; font-size:12px; font-weight:bold; padding-bottom: 10px;">KELAS : ' . htmlspecialchars($nama_rombel) . ' &nbsp;&nbsp;&nbsp; Wali Kelas : ' . htmlspecialchars($nama_walas) . '</th></tr>';

// Header Kolom Tabel
echo '<tr style="background:#d9ead3; text-align:center; font-weight:bold;">';
echo '<th style="width:3%;">NO</th>';
echo '<th style="width:20%;">NAMA</th>'; // Sedikit lebar
echo '<th style="width:12%;">NO INDUK</th>';
echo '<th style="width:30%;">ALAMAT</th>'; // Sedikit lebar
echo '<th style="width:10%;">No HP</th>';
echo '<th style="width:10%;">Nama Ayah</th>';
echo '<th style="width:10%;">Nama Ibu</th>';
echo '<th style="width:5%;">TANDA TANGAN</th>'; // Sedikit lebih kecil
echo '</tr>';

// Baris Data Siswa
$i = 1;
if (!empty($siswa_list)) {
    foreach ($siswa_list as $siswa) {
        echo '<tr>';
        echo '<td style="text-align:center;">' . $i . '</td>';
        echo '<td>' . htmlspecialchars($siswa->nama_siswa ?? '-') . '</td>';
        // Format No Induk sebagai teks agar 0 di depan tidak hilang
        echo '<td style="mso-number-format:\'@\';">' . htmlspecialchars($siswa->no_induk ?? '-') . '</td>';
        echo '<td>' . htmlspecialchars($siswa->alamat ?? '-') . '</td>';
        // Format No HP sebagai teks
        echo '<td style="mso-number-format:\'@\';">' . htmlspecialchars($siswa->no_hp ?? '-') . '</td>';
        echo '<td>' . htmlspecialchars($siswa->nama_ayah ?? '-') . '</td>';
        echo '<td>' . htmlspecialchars($siswa->nama_ibu ?? '-') . '</td>';
        echo '<td></td>'; // Kolom tanda tangan kosong
        echo '</tr>';
        $i++;
    }
} else {
    echo '<tr><td colspan="8" style="text-align:center;">Tidak ada siswa aktif di rombel ini.</td></tr>';
}

echo '</table>';
echo '</body></html>';
