<?php
// Ambil data dari controller
$siswa = $data['siswa'];
$sekolah = $data['sekolah'];
$no_absen = $data['no_absen'] ?? '-';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cover Rapor - <?= htmlspecialchars($siswa->nama_siswa); ?></title>
    <style>
        /* Pengaturan Halaman Dasar dari Kode Anda */
        @page {
            /* Margin 30px ~ 0.8cm. */
            margin: 30px;
            size: A4;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Book Antiqua', 'Palatino', 'Palatino Linotype', 'Palatino LT STD', Georgia, serif;
            /* Font baru dengan fallback */
            /* Font rapor */
            font-size: 12pt;
            /* Ukuran font rapor */
            line-height: 1.4;
        }

        /* --- Global --- */
        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        /* --- Page Break --- */
        .page-break {
            page-break-after: always;
        }

        /* --- STRUKTUR COVER DARI KODE ANDA --- */
        .halaman-cover {
            position: relative;
            height: 100%;
            page-break-after: always;
            page-break-inside: avoid;
            box-sizing: border-box;
        }

        .frame-luar {
            position: absolute;
            top: 1.4cm;
            left: 1.4cm;
            right: 1.4cm;
            bottom: 1.4cm;
            border: 3px solid blue;
            z-index: 10;
        }

        .frame-dalam {
            position: absolute;
            top: 1.55cm;
            left: 1.55cm;
            right: 1.55cm;
            bottom: 1.55cm;
            border: 1px solid blue;
            z-index: 20;
        }

        .konten-cover {
            position: absolute;
            top: 1.55cm;
            left: 1.55cm;
            right: 1.55cm;
            bottom: 1.55cm;
            padding: 25px;
            /* Padding konten di dalam frame */
            overflow: hidden;
            text-align: center;
            z-index: 1;
            box-sizing: border-box;
        }

        /* --- AKHIR STRUKTUR COVER --- */

        /* --- Styling Elemen Konten di dalam .konten-cover --- */
        .cover-logo-container {
            margin-top: 1cm;
            margin-bottom: 5px;
        }

        .cover-logo {
            width: 180px;
            height: auto;
        }

        /* Ukuran logo dari PDF */
        .cover-school-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 3cm;
        }

        .cover-title-container {
            margin-bottom: 3cm;
        }

        .cover-title-container2 {
            margin-bottom: 2cm;
        }

        .cover-title-container3 {
            margin-bottom: 1cm;
        }

        .cover-title {
            font-size: 12pt;
            line-height: 2.5;
            font-weight: normal;
        }

        .cover-label {
            font-size: 12pt;
            margin-bottom: 8px;
        }

        .cover-text-box {
            /* Kotak Nama & NISN */
            border: 0.3px solid #2c2b2bff;
            padding: 6px 15px;
            margin: 0 auto;
            min-width: 60%;
            max-width: 80%;
            min-height: 0.8cm;
            display: table;
            text-align: center;
            font-size: 12pt;
            font-weight: normal;
            box-sizing: border-box;
            background-color: white;
            vertical-align: middle;
        }

        .cover-text-box>span {
            display: table-cell;
            vertical-align: middle;
        }

        .cover-nisn-label {
            margin-top: 1.5cm;
            margin-bottom: 8px;
        }

        .cover-ministry {
            /* Tidak perlu absolut lagi */
            margin-top: 2cm;
            padding-bottom: 1cm;
            font-size: 12pt;
            line-height: 1.5;
            font-weight: normal;
        }

        /* --- Struktur & Styling Halaman Identitas --- */
        .konten-selanjutnya {
            padding: 1.5cm;
            margin-top: 0;
            /* @page margin akan memberi jarak dari tepi */
            page-break-before: avoid;
        }

        .school-id-page,
        .student-id-page {
            margin-top: 0;
            padding: 0;
            /* Padding diatur oleh margin @page */
        }

        .school-id-page {
            page-break-after: always;
        }

        /* Pisahkan hal id sekolah & siswa */

        .school-id-title {
            font-size: 12pt;
            font-weight: normal;
            text-align: center;
            margin-bottom: 1.5cm;
            line-height: 1;
        }

        .school-id-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.5cm;
            margin-left: 1cm;
        }

        .school-id-table td {
            padding: 3px 5px;
            vertical-align: top;
            font-size: 12pt;
            line-height: 3;
        }

        .school-id-table .label {
            width: 20%;
            font-weight: normal;
        }

        /* Label Bold */
        .school-id-table .colon {
            width: 2%;
            text-align: center;
            font-weight: normal;
        }

        /* Titik Dua Bold */
        .school-id-table .value {
            width: 78%;
            padding-left: 0.5cm;
        }

        .student-id-title {
            margin-top: 0px;
            margin-bottom: 1.8cm;
            font-size: 12pt;
            font-weight: normal;
            text-align: center;
        }

        .identitas-siswa {
            margin-top: -1.8cm;
        }

        .student-id-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.5cm;
        }

        .student-id-table td {
            padding: 3px 5px;
            vertical-align: top;
            font-size: 12pt;
            line-height: 1.2;
        }

        .student-id-table .nomor {
            width: 3%;
            text-align: right;
            padding-right: 5px;
        }

        .student-id-table .label {
            width: 30%;
        }

        .student-id-table .colon {
            width: 2%;
            text-align: center;
        }

        .student-id-table .value {
            padding-left: 0.8cm;
            width: 65%;
        }

        .student-id-table .sub-label {
            padding-left: 20px;
            width: 38%;
        }

        .pas-foto {
            width: 2cm;
            height: 2.8cm;
            border: 1px solid #000;
            text-align: center;
            font-size: 10pt;
            color: #555;
            float: center;
            margin-top: -3.5cm;
            margin-left: 6.5cm;
            line-height: 1cm;
            z-index: -10;
        }

        .signature-box {
            margin-top: 1.5cm;
            clear: both;
        }

        .signature-box table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
        }

        .signature-box td {
            padding: 2px;
            vertical-align: top;
            font-size: 12pt;
        }

        .signature-box .left-sig,
        .signature-box .right-sig {
            width: 50%;
            text-align: left;
        }

        .signature-box .spacer {
            height: 2.3cm;
        }

        .kepsek-name {
            margin-top: 0px;
        }

        /* --- Footer (Fixed) --- */
        .footer {
            position: fixed;
            bottom: 6mm;
            /* Jarak dari tepi bawah kertas */
            left: 1.5cm;
            /* Sama dengan margin @page */
            right: 1.5cm;
            /* Sama dengan margin @page */
            height: auto;
            font-size: 7pt;
            font-style: italic;
            color: #333;
            /* border-top: 1px solid #000; Dihilangkan */
            padding-top: 2mm;
            box-sizing: border-box;
            z-index: 100;
        }

        .footer table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer td {
            vertical-align: bottom;
            padding: 0;
        }

        .footer-left {
            text-align: left;
            width: 45%;
        }

        .footer-center {
            text-align: center;
            width: 10%;
        }

        .footer-right {
            text-align: right;
            width: 45%;
        }
    </style>
</head>

<body>

    <div class="footer">
        <table>
            <tr>
                <td class="footer-left"><?= htmlspecialchars($no_absen); ?>. <?= htmlspecialchars($siswa->nama_siswa); ?> - <?= htmlspecialchars($siswa->nama_rombel ?? '-'); ?></td>
                <td class="footer-center">
                    <script type="text/php">
                        if (isset($pdf)) {
                              $text = "{PAGE_NUM}";
                              $font = $fontMetrics->get_font("Times New Roman", "normal");
                              $size = 8; $color = array(0.2, 0.2, 0.2);
                              $width = $fontMetrics->get_text_width($text, $font, $size);
                              $marginLeftPoints = 30 * 0.75; // Konversi 30px ke points (sesuaikan jika margin @page Anda beda)

                                $x = (($pdf->get_width() - $width) / 1.9) + 8; // Tambah 3 points
                              // Posisi Y: sedikit di atas tepi bawah kertas
                              $y = $pdf->get_height() - 26; // Sesuaikan angka 40 jika perlu
                              $pdf->page_text($x, $y, $text, $font, $size, $color);
                          }
                      </script>
                </td>
                <td class="footer-right">Dicetak dari <?= htmlspecialchars($sekolah->versi_erapor ?? 'v?.?.?'); ?></td>
            </tr>
        </table>
    </div>


    <div class="halaman-cover">
        <div class="frame-luar"></div>
        <div class="frame-dalam"></div>
        <div class="konten-cover">
            <div class="cover-logo-container">
                <?php
                $logoPath = BASEURL . '/assets/images/logo_smki.png'; // Ganti default path
                if (!empty($sekolah->logo_sekolah)) {
                    // Asumsi $sekolah->logo_sekolah menyimpan path relatif dari folder public,
                    // contoh: 'images/logo/logo_sekolah.png' atau './images/logo/logo_sekolah.png'

                    // 1. Bersihkan path relatif (hapus './' atau '/' di awal jika ada)
                    $logoRelativePath = ltrim($sekolah->logo_sekolah, './');
                    $logoRelativePath = ltrim($logoRelativePath, '/'); // Hapus juga '/' di awal

                    // 2. Cek keberadaan file di server menggunakan path relatif (dari index.php di public)
                    if (file_exists($logoRelativePath)) {
                        // 3. Jika file ada, buat URL lengkapnya
                        $logoPath = BASEURL . '/' . $logoRelativePath;
                    } else {
                        // Opsional: Handle jika file dari DB tidak ditemukan, bisa biarkan pakai default
                        // error_log("File logo tidak ditemukan: " . $logoRelativePath); // Catat error jika perlu
                    }
                }
                ?>
                <img src="<?= $logoPath ?>" alt="Logo Sekolah" class="cover-logo">
                <div class="cover-school-name text-uppercase"></div>
            </div>

            <div class="cover-title-container">
                <div class="cover-title text-uppercase">RAPOR PESERTA DIDIK</div>
                <div class="cover-title text-uppercase">SEKOLAH MENENGAH KEJURUAN</div>
                <div class="cover-title text-uppercase">(SMK)</div>
            </div>

            <div class="cover-title-container2">
                <div class="cover-label">Nama Peserta Didik:</div>
                <div class="cover-text-box text-bold text-uppercase"><span><?= htmlspecialchars($siswa->nama_siswa); ?></span></div>
            </div>
            <div class="cover-title-container3">
                <div class="cover-nisn-label">NISN:</div>
                <div class="cover-text-box text-bold nisn"><span><?= htmlspecialchars($siswa->nisn ?? '-'); ?></span></div>
            </div>

            <div class="cover-ministry text-uppercase">
                KEMENTERIAN PENDIDIKAN DASAR DAN MENENGAH<br>
                REPUBLIK INDONESIA
            </div>

        </div>
    </div>
    <div class="konten-selanjutnya">
        <div class="school-id-page">
            <div class="school-id-title text-uppercase">RAPOR PESERTA DIDIK<br>SEKOLAH MENENGAH KEJURUAN<br>(SMK)</div>
            <div class="identitas-sekolah">
                <table class="school-id-table">
                    <tr>
                        <td class="label">Nama Sekolah</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($sekolah->nama_sekolah ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">NPSN / NSS</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($sekolah->npsn ?? '-'); ?> / <?= htmlspecialchars($sekolah->nss ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($sekolah->alamat ?? '-'); ?><br>Kode Pos <?= htmlspecialchars($sekolah->kode_pos ?? '-'); ?> Telp. <?= htmlspecialchars($sekolah->telepon ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Kelurahan</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($sekolah->kelurahan ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Kecamatan</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($sekolah->kecamatan ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Kabupaten/Kota</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($sekolah->kota ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Provinsi</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($sekolah->provinsi ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Website</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($sekolah->website ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($sekolah->email ?? '-'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    <div class="konten-selanjutnya">
        <div class="student-id-page">
            <div class="identitas-siswa">
                <h4 class="student-id-title">KETERANGAN TENTANG DIRI PESERTA DIDIK</h4>
                <table class="student-id-table">
                    <tr>
                        <td class="nomor">1</td>
                        <td class="label ">Nama Peserta Didik (Lengkap)</td>
                        <td class="colon">:</td>
                        <td class="value text-uppercase"><?= htmlspecialchars($siswa->nama_siswa); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">2</td>
                        <td class="label">Nomor Induk / NISN</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->no_induk ?? '-'); ?> / <?= htmlspecialchars($siswa->nisn ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">3</td>
                        <td class="label">Tempat, Tanggal Lahir</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->tmpt_lhr ?? '-'); ?>, <?= function_exists('tanggal_indo') ? tanggal_indo($siswa->tgl_lhr) : $siswa->tgl_lhr; ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">4</td>
                        <td class="label">Jenis Kelamin</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->jenis_kelamin ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">5</td>
                        <td class="label">Agama</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->agama ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">6</td>
                        <td class="label">Status dalam Keluarga</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->status_dlm_keluarga ?? 'Anak Kandung'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">7</td>
                        <td class="label">Anak Ke</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->anak_ke ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">8</td>
                        <td class="label">Alamat Peserta Didik</td>
                        <td class="colon">:</td>
                        <td class="value"> <?= htmlspecialchars($siswa->alamat ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">9</td>
                        <td class="label">Nomor Telepon Rumah / HP</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->no_tlp ?? $siswa->no_hp ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">10</td>
                        <td class="label">Sekolah Asal (SMP/MTs)</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->asal_smp ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">11</td>
                        <td class="label">Diterima di sekolah ini</td>
                        <td class="colon">:</td>
                        <td class="value"></td>
                    </tr>
                    <tr>
                        <td class="nomor"></td>
                        <td class="label sub-label">a. Di kelas</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->tingkat ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor"></td>
                        <td class="label sub-label">b. Pada tanggal</td>
                        <td class="colon">:</td>
                        <td class="value"><?= function_exists('tanggal_indo') ? tanggal_indo($siswa->diterima_tgl) : $siswa->diterima_tgl; ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">12</td>
                        <td class="label">Nama Orang Tua</td>
                        <td class="colon">:</td>
                        <td class="value"></td>
                    </tr>
                    <tr>
                        <td class="nomor"></td>
                        <td class="label sub-label">a. Ayah</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->nama_ayah ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor"></td>
                        <td class="label sub-label">b. Ibu</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->nama_ibu ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">13</td>
                        <td class="label">Alamat Orang Tua</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->alamat_ayah ?? $siswa->alamat_ibu ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor"></td>
                        <td class="label sub-label">Nomor Telepon / HP</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->hp_ayah ?? $siswa->hp_ibu ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">14</td>
                        <td class="label">Pekerjaan Orang Tua</td>
                        <td class="colon">:</td>
                        <td class="value"></td>
                    </tr>
                    <tr>
                        <td class="nomor"></td>
                        <td class="label sub-label">a. Ayah</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->pekerjaan_ayah ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor"></td>
                        <td class="label sub-label">b. Ibu</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->pekerjaan_ibu ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">15</td>
                        <td class="label">Nama Wali Peserta Didik</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->nama_wali ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">16</td>
                        <td class="label">Alamat Wali Peserta Didik</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->alamat_wali ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor"></td>
                        <td class="label sub-label">Nomor Telepon / HP</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->hp_wali ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="nomor">17</td>
                        <td class="label">Pekerjaan Wali Peserta Didik</td>
                        <td class="colon">:</td>
                        <td class="value"><?= htmlspecialchars($siswa->pekerjaan_wali ?? '-'); ?></td>
                    </tr>
                </table>
            </div>
            <div class="signature-box">
                <table>
                    <tr>
                        <td class="left-sig">

                        </td>
                        <td style="width: 20%;"></td>
                        <td class="right-sig">
                            <?= htmlspecialchars($sekolah->kota ?? 'Blitar'); ?>, <?= function_exists('tanggal_indo') ? tanggal_indo($siswa->diterima_tgl) : date('d-m-Y'); ?> <br> Kepala Sekolah,
                            <div class="spacer"></div>
                            <div class="kepsek-name"><?= htmlspecialchars($sekolah->nama_kepsek ?? '-'); ?></div>
                            NIP. <?= htmlspecialchars($sekolah->nip_kepsek ?? '-'); ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="pas-foto"><span>Pas Foto<br>3 x 4</span></div>
        </div>
    </div>

    <div class="konten-selanjutnya">
        <div class="student-id-page">
            <div class="identitas-siswa">
                <h4 class="student-id-title">PETUNJUK PENGISIAN</h4>
                <table class="student-id-table">
                    <tr style="padding: 10px">
                        <td class="nomor">1.</td>
                        <td class="label">Rapor merupakan ringkasan hasil penilaian terhadap seluruh aktivitas pembelajaran yang dilakukan peserta didik dalam kurun waktu tertentu;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">2.</td>
                        <td class="label">Rapor dipergunakan selama peserta didik yang bersangkutan mengikuti seluruh program pembelajaran di Sekolah Menengah Kejuruan tersebut;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">3.</td>
                        <td class="label">Identitas Sekolah diisi dengan data yang sesuai dengan keberadaan Sekolah Menengah Kejuruan, penulisan nama sekolah ditulis menggunakan dengan Kapital Ondercast di setiap awal kata contoh (SMK Nusa Bangsa), untuk halaman depan di tulis dengan huruf kapital;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">4.</td>
                        <td class="label">Keterangan tentang diri Peserta didik diisi lengkap sesuai ijazah sebelumnya atau akta kelahiran;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">5.</td>
                        <td class="label">Rapor harus dilengkapi dengan pas foto berwarna dengan latar belakang merah (3 x 4) serta menggunakan baju putih seragam dan pengisiannya dilakukan oleh Wali Kelas;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">6.</td>
                        <td class="label">Capaian peserta didik dalam kompetensi pengetahuan dan kompetensi keterampilan ditulis dalam bentuk angka dan predikat untuk masing-masing mata pelajaran;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">7.</td>
                        <td class="label">Predikat ditulis dalam bentuk huruf sesuai kriteria;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">8.</td>
                        <td class="label">Catatan akademik ditulis dengan kalimat positif sesuai capaian yang diperoleh peserta didik;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">9.</td>
                        <td class="label">Penjelasan lebih detil mengenai capaian kompetensi peserta didik dapat dilihat pada leger;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">10.</td>
                        <td class="label">Laporan Praktik Kerja Lapangan diisi berdasarkan kegiatan praktik kerja yang diikuti oleh peserta didik di industri/perusahaan mitra;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">11.</td>
                        <td class="label">Laporan Ekstrakurikuler diisi berdasarkan kegiatan ekstrakurikuler yang diikuti oleh peserta didik;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">12.</td>
                        <td class="label">Ketidakhadiran diisi dengan data akumulasi ketidakhadiran peserta didik karena sakit, izin, atau tanpa keterangan selama satu semester.</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">13.</td>
                        <td class="label">Keterangan kenaikan kelas diisi dengan putusan apakah peserta didik naik kelas yang ditentukan melalui rapat dewan guru.</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">14.</td>
                        <td class="label">Deskripsi perkembangan karakter diisi dengan simpulan perkembangan peserta didik terkait penumbuhan karakter baik yang dilakukan secara terprogram oleh sekolah maupun yang muncul secara spontan dari peserta didik;</td>
                    </tr>
                    <tr style="padding: 10px;">
                        <td class="nomor">15.</td>
                        <td class="label">Catatan perkembangan karakter diisikan hal-hal yang tidak tercantum pada deskripsi perkembangan karakter termasuk prestasi yang diraih peserta didik pada semester berjalan dan simpulan dari perkembangan karakter peserta didik pada semester berjalan jika dikomparasi dengan semester sebelumnya.</td>
                    </tr>
                </table>
            </div>
        </div>

</body>

</html>