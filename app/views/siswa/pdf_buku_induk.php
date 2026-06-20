<?php
// Tidak perlu definisi fungsi tanggal_indo dan formatPenghasilan di sini lagi
// karena diasumsikan sudah ada dari helper global

// Ambil variabel dari $data yang dikirim controller
$row = $data['siswa'];
$mutasi_jenis = $data['mutasi']['jenis'] ?? '-';
$mutasi_tgl = $data['mutasi']['tanggal'] ?? '-';
$mutasi_from = $data['mutasi']['asal'] ?? '-';
$mutasi_to = $data['mutasi']['tujuan'] ?? '-';
$mutasi_alasan = $data['mutasi']['alasan'] ?? '-';
$no_absen = $data['no_absen'] ?? '-';
$no_induk_display = $data['no_induk_display'] ?? '-';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Buku Induk <?= htmlspecialchars($row->nama_siswa); ?></title>
    <style>
        /* Salin SEMUA CSS dari kode sebelumnya ke sini */
        @page {
            margin: 12mm 16mm 12mm 25mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            line-height: 1.3;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .foto-row {
            width: 100%;
            margin-bottom: 10px;
        }

        .foto-box {
            display: inline-block;
            width: 2.79cm;
            height: 3.81cm;
            border: 1px solid #3f3f3fff;
            margin: 0 15px;
            vertical-align: top;
            text-align: center;
            font-size: 9px;
            color: #636262;
            font-style: italic;
            position: relative;
        }

        .foto-box span {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 6px;
            margin-bottom: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }

        td {
            vertical-align: top;
            padding: 1px 4px;
        }

        .label {
            width: 45%;
        }

        .value {
            width: 55%;
        }

        .nomor {
            text-align: right;
            width: 25px;
            padding-right: 5px;
        }

        .colon {
            width: 10px;
            text-align: center;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 18mm;
            text-align: center;
            font-size: 9px;
            color: #636262ff;
        }

        .first-page {
            page-break-after: always;
        }

        .page-break {
            page-break-after: always;
            height: 0;
            line-height: 0;
        }

        .sub-label {
            padding-left: 20px;
        }

        .section-break {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="footer">Kelas : <?= htmlspecialchars($row->nama_rombel ?? '-') ?> &nbsp;|&nbsp; No Absen : <?= htmlspecialchars($no_absen) ?> &nbsp;|&nbsp; No Induk : <?= htmlspecialchars($no_induk_display) ?></div>

    <div class="first-page">
        <div class="judul">LEMBARAN BUKU INDUK SISWA</div>
        <div class="foto-row" style="text-align:right;">
            <div class="foto-box"><span>Pas Foto 3x4</span></div>
            <div class="foto-box"><span>Pas Foto 3x4</span></div>
        </div>
        <table style="margin-top: 15px;">
            <tr>
                <td colspan="4" class="section-title">A. KETERANGAN TENTANG DIRI SISWA</td>
            </tr>
            <tr>
                <td class="nomor">1.</td>
                <td class="label">Nama Lengkap</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nama_siswa ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">2.</td>
                <td class="label">Nama Panggilan</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nama_panggilan ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">3.</td>
                <td class="label">Nomor Induk</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->no_induk ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">4.</td>
                <td class="label">NISN</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nisn ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">5.</td>
                <td class="label">NIK</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nik ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">6.</td>
                <td class="label">NKK</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nkk ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">7.</td>
                <td class="label">Nomor Akta Kelahiran</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->no_akta ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">8.</td>
                <td class="label">Jenis kelamin</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->jenis_kelamin ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">9.</td>
                <td class="label">Tempat dan Tanggal Lahir</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->tmpt_lhr ?? '-') ?>, <?= tanggal_indo($row->tgl_lhr) // Gunakan helper global 
                                                                                    ?></td>
            </tr>
            <tr>
                <td class="nomor">10.</td>
                <td class="label">Agama</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->agama ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">11.</td>
                <td class="label">Kewarganegaraan/Suku</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->kewarganegaraan ?? '-') ?> / <?= htmlspecialchars($row->suku ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">12.</td>
                <td class="label">Anak ke-</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->anak_ke ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">13.</td>
                <td class="label">Jumlah saudara kandung</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->jml_sdr_kandung ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">14.</td>
                <td class="label">Jumlah saudara tiri</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->jml_sdr_tiri ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">15.</td>
                <td class="label">Jumlah saudara angkat</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->jml_sdr_angkat ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">16.</td>
                <td class="label">Status anak</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->yatim_piatu ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">17.</td>
                <td class="label">Bahasa sehari-hari di rumah</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->bahasa ?? '-') ?></td>
            </tr>
        </table>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">B. KETERANGAN TEMPAT TINGGAL</td>
            </tr>

            <tr>
                <td class="nomor">18.</td>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td class="value">RT. <?= htmlspecialchars($row->rt ?? '-') ?>&nbsp;&nbsp; RW. <?= htmlspecialchars($row->rw ?? '-') ?>&nbsp;&nbsp; Dusun <?= htmlspecialchars($row->dusun ?? '-') ?> <br> Desa/Kel <?= htmlspecialchars($row->desa ?? '-') ?> <br> Kec. <?= htmlspecialchars($row->kec ?? '-') ?> &nbsp;&nbsp;
                    <?= htmlspecialchars($row->kab ?? '-') ?>&nbsp;&nbsp;<br>Kode Pos <?= htmlspecialchars($row->kd_pos ?? '-') ?>&nbsp;&nbsp; Provinsi <?= htmlspecialchars($row->provinsi ?? '-') ?>
                </td>
            </tr>
            <tr>
                <td class="nomor">19.</td>
                <td class="label">Nomor telepon / handphone</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->no_tlp ?? $row->no_hp ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">20.</td>
                <td class="label">Tinggal bersama</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->tinggal_bersama ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">21.</td>
                <td class="label">Jarak rumah / Waktu tempuh</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->jarak_rumah ?? '-') ?> / <?= htmlspecialchars($row->wkt_tempuh ?? '-') ?> </td>
            </tr>
            <tr>
                <td class="nomor">22.</td>
                <td class="label">Transportasi ke sekolah</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->transportasi ?? '-') ?></td>
            </tr>
        </table>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">C. KETERANGAN KESEHATAN</td>
            </tr>
            <tr>
                <td class="nomor">23.</td>
                <td class="label">Golongan darah</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->gol_darah ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">24.</td>
                <td class="label">Penyakit yang pernah diderita</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->penyakit ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">25.</td>
                <td class="label">Kelainan jasmani</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->kelainan_jasmani ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">26.</td>
                <td class="label">Tinggi dan berat badan</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->tb ?? '-') ?> cm / <?= htmlspecialchars($row->bb ?? '-') ?> Kg</td>
            </tr>
        </table>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">D. KETERANGAN PENDIDIKAN SEBELUMNYA</td>
            </tr>
            <tr>
                <td class="nomor">27.</td>
                <td class="label" colspan="3" style="font-weight: bold; text-decoration: underline;">Pendidikan Dasar</td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">a. Sekolah asal SD/MI</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->asal_sd ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">b. NPSN SD/MI</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->npsn_sd ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">28.</td>
                <td class="label" colspan="3" style="font-weight: bold; text-decoration: underline;">Pendidikan Menengah Pertama</td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">a. Lulusan dari</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->pend_sebelumnya ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">b. Sekolah asal SMP/MTs</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->asal_smp ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">c. Alamat sekolah asal</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->alamat_smp ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">d. NPSN sekolah SMP/MTs</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->npsn_smp ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">e. Tgl ijazah</td>
                <td class="colon">:</td>
                <td class="value"><?= tanggal_indo($row->tgl_ijazah_smp) // Gunakan helper global 
                                    ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">f. Tahun ijazah</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->th_ijazah_smp ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">g. Lama belajar</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->lama_belajar_smp ?? '-') ?> Tahun</td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">h. Nomor seri ijazah</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->seri_ijazah_smp ?? '-') ?></td>
            </tr>
        </table>
    </div>

    <div>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">E. KETERANGAN PENDIDIKAN (Sekolah Ini)</td>
            </tr>
            <tr>
                <td class="nomor">29.</td>
                <td class="label" colspan="3" style="font-weight: bold; text-decoration: underline;">Diterima di sekolah ini</td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">a. Tingkat</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->tingkat ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">b. Bidang Keahlian</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->bid_keahlian ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">c. Program Keahlian</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->prog_keahlian ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">d. Konsentrasi Keahlian</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->jurusan ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label sub-label">e. Diterima tanggal</td>
                <td class="colon">:</td>
                <td class="value"><?= tanggal_indo($row->diterima_tgl) // Gunakan helper global 
                                    ?></td>
            </tr>
        </table>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">F. KETERANGAN TENTANG AYAH KANDUNG</td>
            </tr>
            <tr>
                <td class="nomor">30.</td>
                <td class="label">Nama lengkap</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nama_ayah ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">31.</td>
                <td class="label">NIK</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nik_ayah ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">32.</td>
                <td class="label">Tempat dan Tanggal Lahir</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->tmpt_lhr_ayah ?? '-') ?>, <?= tanggal_indo($row->tgl_lhr_ayah) // Gunakan helper global 
                                                                                        ?></td>
            </tr>
            <tr>
                <td class="nomor">33.</td>
                <td class="label">Agama</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->agama_ayah ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">34.</td>
                <td class="label">Kewarganegaraan</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->kewarganegaraan_ayah ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">35.</td>
                <td class="label">Pendidikan terakhir</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->pend_ayah ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">36.</td>
                <td class="label">Pekerjaan</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->pekerjaan_ayah ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">37.</td>
                <td class="label">Penghasilan</td>
                <td class="colon">:</td>
                <td class="value"><?= formatPenghasilan($row->penghasilan_ayah) // Gunakan helper global 
                                    ?></td>
            </tr>
            <tr>
                <td class="nomor">38.</td>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->alamat_ayah ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">39.</td>
                <td class="label">Nomor telepon / handphone</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->hp_ayah ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">40.</td>
                <td class="label">Status</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->hidup_mati_ayah ?? '-') ?></td>
            </tr>
        </table>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">G. KETERANGAN TENTANG IBU KANDUNG</td>
            </tr>
            <tr>
                <td class="nomor">41.</td>
                <td class="label">Nama lengkap</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nama_ibu ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">42.</td>
                <td class="label">NIK</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nik_ibu ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">43.</td>
                <td class="label">Tempat dan Tanggal Lahir</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->tmpt_lhr_ibu ?? '-') ?>, <?= tanggal_indo($row->tgl_lhr_ibu) // Gunakan helper global 
                                                                                        ?></td>
            </tr>
            <tr>
                <td class="nomor">44.</td>
                <td class="label">Agama</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->agama_ibu ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">45.</td>
                <td class="label">Kewarganegaraan</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->kewarganegaraan_ibu ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">46.</td>
                <td class="label">Pendidikan terakhir</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->pend_ibu ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">47.</td>
                <td class="label">Pekerjaan</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->pekerjaan_ibu ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">48.</td>
                <td class="label">Penghasilan</td>
                <td class="colon">:</td>
                <td class="value"><?= formatPenghasilan($row->penghasilan_ibu) // Gunakan helper global 
                                    ?></td>
            </tr>
            <tr>
                <td class="nomor">49.</td>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->alamat_ibu ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">50.</td>
                <td class="label">Nomor telepon / handphone</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->hp_ibu ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">51.</td>
                <td class="label">Status</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->hidup_mati_ibu ?? '-') ?></td>
            </tr>
        </table>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">H. KETERANGAN TENTANG WALI</td>
            </tr>
            <tr>
                <td class="nomor">52.</td>
                <td class="label">Nama lengkap</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nama_wali ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">53.</td>
                <td class="label">NIK</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->nik_wali ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">54.</td>
                <td class="label">Tempat dan Tanggal Lahir</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->tmpt_lhr_wali ?? '-') ?>, <?= tanggal_indo($row->tgl_lhr_wali) // Gunakan helper global 
                                                                                        ?></td>
            </tr>
            <tr>
                <td class="nomor">55.</td>
                <td class="label">Agama</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->agama_wali ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">56.</td>
                <td class="label">Kewarganegaraan</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->kewarganegaraan_wali ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">57.</td>
                <td class="label">Pendidikan terakhir</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->pend_wali ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">58.</td>
                <td class="label">Pekerjaan</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->pekerjaan_wali ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">59.</td>
                <td class="label">Penghasilan</td>
                <td class="colon">:</td>
                <td class="value"><?= formatPenghasilan($row->penghasilan_wali) // Gunakan helper global 
                                    ?></td>
            </tr>
            <tr>
                <td class="nomor">60.</td>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->alamat_wali ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">61.</td>
                <td class="label">Nomor telepon / handphone</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->hp_wali ?? '-') ?></td>
            </tr>
        </table>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">I. KEGEMARAN / HOBI</td>
            </tr>
            <tr>
                <td class="nomor">62.</td>
                <td class="label">Kesenian</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->kesenian ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">63.</td>
                <td class="label">Olahraga</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->olahraga ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">64.</td>
                <td class="label">Kemasyarakatan / Organisasi</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->organisasi ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">65.</td>
                <td class="label">Cita - cita</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->cita_cita ?? '-') ?></td>
            </tr>
            <tr>
                <td class="nomor">66.</td>
                <td class="label">Lain - lain</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($row->lain_lain ?? '-') ?></td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <div>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">J. KETERANGAN MENINGGALKAN SEKOLAH</td>
            </tr>
            <tr>
                <td class="nomor">67.</td>
                <td class="label">Tamat / LULUS</td>
                <td class="colon">:</td>
                <td class="value"><?= (($row->id_status == 4) ? 'LULUS' : '-') // Asumsi ID 4 = Lulus 
                                    ?></td>
            </tr>
            <tr>
                <td class="nomor">68.</td>
                <td class="label">Tahun Kelulusan</td>
                <td class="colon">:</td>
                <td class="value"><?= (($row->id_status == 4) ? htmlspecialchars($row->tahun_lulus ?? '-') : '-') ?></td>
            </tr>
        </table>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">K. MUTASI MASUK/KELUAR</td>
            </tr>
            <tr>
                <td class="nomor">69.</td>
                <td class="label">a. Jenis Mutasi</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($mutasi_jenis) ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label">b. Tanggal Keluar / Masuk</td>
                <td class="colon">:</td>
                <td class="value"><?= tanggal_indo($mutasi_tgl) // Gunakan helper global 
                                    ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label">c. Pindahan dari sekolah</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($mutasi_from) ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label">d. Alasan pindah sekolah</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($mutasi_alasan) ?></td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label">e. Sekolah yang dituju</td>
                <td class="colon">:</td>
                <td class="value"><?= htmlspecialchars($mutasi_to) ?></td>
            </tr>
        </table>
        <table class="section-break">
            <tr>
                <td colspan="4" class="section-title">L. KETERANGAN SETELAH SELESAI PENDIDIKAN</td>
            </tr>
            <tr>
                <td class="nomor">70.</td>
                <td class="label">a. Melanjutkan pendidikan ke-</td>
                <td class="colon">:</td>
                <td class="value">-</td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label">b. Bekerja</td>
                <td class="colon">:</td>
                <td class="value">-</td>
            </tr>
            <tr>
                <td class="nomor"></td>
                <td class="label">c. Tanggal Mulai Bekerja</td>
                <td class="colon">:</td>
                <td class="value">-</td>
            </tr>
        </table>
    </div>

</body>

</html>