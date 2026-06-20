<?php

/**
 * Helper function to format date into Indonesian format.
 * * @param string $tanggal Input date string (Y-m-d or Y-m-d H:i:s).
 * @param bool $cetak_hari Whether to include the day name (e.g., "Rabu, 22 Oktober 2025"). Default is false.
 * @return string Formatted Indonesian date string.
 */
function tanggal_indo($tanggal, $cetak_hari = false)
{
    // Check if the input is valid
    if (empty($tanggal) || $tanggal == '0000-00-00' || $tanggal == '0000-00-00 00:00:00') {
        return '-'; // Return strip if date is invalid or empty
    }

    $hari = array(
        1 => 'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
        'Minggu'
    );

    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    // Explode date and time if exists
    $split    = explode(' ', $tanggal);
    $tgl_indo = $split[0]; // Take only the date part YYYY-MM-DD

    // Check if the date part is valid after splitting
    if (empty($tgl_indo) || $tgl_indo == '0000-00-00') {
        return '-';
    }

    $split_tgl = explode('-', $tgl_indo);

    // Check if explode result is valid
    if (count($split_tgl) != 3 || !checkdate((int)$split_tgl[1], (int)$split_tgl[2], (int)$split_tgl[0])) {
        return '-'; // Return strip if date components are invalid
    }

    $tgl   = $split_tgl[2];
    $bln   = $split_tgl[1];
    $thn   = $split_tgl[0];

    // Format the date string
    $result = $tgl . ' ' . $bulan[(int)$bln] . ' ' . $thn;

    // Optionally add the day name
    if ($cetak_hari) {
        $num = date('N', strtotime($tgl_indo));
        $result = $hari[$num] . ', ' . $result;
    }

    // Optionally add the time part back if it exists
    if (isset($split[1])) {
        $result .= ' ' . $split[1];
    }

    return $result;
}
