<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<?php
// Fungsi helper untuk mencetak indikator log
function cekLogEdit($nama_kolom, $data_log)
{
    if (isset($data_log[$nama_kolom])) {
        $waktu = date('d/m/Y H:i', strtotime($data_log[$nama_kolom]['waktu_edit']));
        $nilai_lama = htmlspecialchars($data_log[$nama_kolom]['nilai_lama'] ?: '(Kosong)');

        // Output HTML indikator (Teks kecil warna merah di bawah input)
        echo '<div class="text-danger small mt-1 fw-bold">';
        echo '<i class="bi bi-exclamation-triangle-fill"></i> Diubah siswa (' . $waktu . '). Sebelumnya: <del class="text-secondary">' . $nilai_lama . '</del>';
        echo '</div>';
    }
}
?>

<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Data Induk Siswa</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= BASEURL; ?>/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript:history.back()">Siswa</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Siswa</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <a href="javascript:history.back()" class="btn btn-secondary"> Kembali </a>
                    <?php if (Auth::checkRole('admin')) : // Tombol edit hanya untuk admin 
                    ?>
                        <a href="<?= BASEURL; ?>/siswa/edit/<?= $data['siswa']->id_induk; ?>" class="btn btn-warning block float-end" title="Edit">
                            <i class="bi bi-pen-fill"></i> Edit
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-2">A. Keterangan Diri Siswa</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0" style="width: 30px;">&nbsp;</td>
                                <td class="p-0" style="width: 30px;">1.</td>
                                <td class="w-35 p-0">Nama</td>
                                <td class="w-1 p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nama_siswa ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">2.</td>
                                <td class="p-0">Nama Panggilan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nama_panggilan ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('nama_panggilan', $data['log_edit']); ?></td>

                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">3.</td>
                                <td class="p-0">Nomor Induk</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->no_induk ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">4.</td>
                                <td class="p-0">NISN (Nomor Induk Siswa Nasional)</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nisn ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">5.</td>
                                <td class="p-0">NIK (Nomor Induk Kependudukan)</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nik_siswa ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">6.</td>
                                <td class="p-0">NKK (Nomor Kartu Keluarga)</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nkk ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">7.</td>
                                <td class="p-0">Jenis Kelamin</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->jenis_kelamin ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">8.</td>
                                <td class="p-0">Tempat dan Tanggal Lahir</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->tmpt_lhr ?? '-'); ?>, <?= tanggal_indo($data['siswa']->tgl_lhr ?? ''); ?></td>
                                <td class="p-0"><?php cekLogEdit('tempat_lahir', $data['log_edit']); ?></td>,<td class="p-0"><?php cekLogEdit('tgl_lhr', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">9.</td>
                                <td class="p-0">Agama</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->agama ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('agama', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">10.</td>
                                <td class="p-0">Kewarganegaraan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->kewarganegaraan ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('kewarganegaraan', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">11.</td>
                                <td class="p-0">Anak Ke-</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->anak_ke ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('anak_ke', $data['log_edit']); ?></td>

                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">12.</td>
                                <td class="p-0">Jumlah Saudara Kandung</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->jml_sdr_kandung ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('jml_sdr_kandung', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">13.</td>
                                <td class="p-0">Jumlah Saudara Tiri</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->jml_sdr_tiri ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('jml_sdr_tiri', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">14.</td>
                                <td class="p-0">Jumlah Saudara Angkat</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->jml_sdr_angkat ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('jml_sdr_angkat', $data['log_edit']); ?></td>

                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">15.</td>
                                <td class="p-0">Status Anak</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->yatim_piatu ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('yatim_piatu', $data['log_edit']); ?></td>

                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">16.</td>
                                <td class="p-0">Bahasa Sehari-hari</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->bahasa ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('bahasa', $data['log_edit']); ?></td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-3">B. Keterangan Tempat Tinggal</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">17.</td>
                                <td class="p-0">Alamat</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->alamat ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('alamat', $data['log_edit']); ?></td>

                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Dusun <?= htmlspecialchars($data['siswa']->dusun ?? '-'); ?> RT <?= htmlspecialchars($data['siswa']->rt ?? '-'); ?> RW <?= htmlspecialchars($data['siswa']->rw ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('dusun', $data['log_edit']); ?></td>,<td class="p-0"><?php cekLogEdit('rt', $data['log_edit']); ?></td>,<td class="p-0"><?php cekLogEdit('rw', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Desa/Kel <?= htmlspecialchars($data['siswa']->desa ?? '-'); ?> Kecamatan <?= htmlspecialchars($data['siswa']->kec ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('desa', $data['log_edit']); ?></td>,<td class="p-0"><?php cekLogEdit('kec', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->kab ?? '-'); ?> Kode Pos <?= htmlspecialchars($data['siswa']->kd_pos ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('kab', $data['log_edit']); ?></td>,<td class="p-0"><?php cekLogEdit('kd_pos', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Provinsi <?= htmlspecialchars($data['siswa']->provinsi ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('provinsi', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">18.</td>
                                <td class="p-0">Nomor Telepon Rumah</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->no_tlp ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('no_tlp', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">19.</td>
                                <td class="p-0">Nomor Handphone</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->no_hp ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('no_hp', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">20.</td>
                                <td class="p-0">Tinggal Bersama</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->tinggal_bersama ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('tinggal_bersama', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">21.</td>
                                <td class="p-0">Jarak Rumah / Waktu Tempuh</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->jarak_rumah ?? '-'); ?> km / <?= htmlspecialchars($data['siswa']->wkt_tempuh ?? '-'); ?> menit</td>
                                <td class="p-0"><?php cekLogEdit('jarak_rumah', $data['log_edit']); ?></td>,<td class="p-0"><?php cekLogEdit('wkt_tempuh', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">22.</td>
                                <td class="p-0">Transportasi</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->transportasi ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('transportasi', $data['log_edit']); ?></td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-3">C. Keterangan Kesehatan</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">23.</td>
                                <td class="p-0">Golongan Darah</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->gol_darah ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('gol_darah', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">24.</td>
                                <td class="p-0">Penyakit yang Pernah Diderita</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->penyakit ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('penyakit', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">25.</td>
                                <td class="p-0">Kelainan Jasmani</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->kelainan_jasmani ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('kelainan_jasmani', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">26.</td>
                                <td class="p-0">Tinggi Badan / Berat Badan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->tb ?? '-'); ?> cm / <?= htmlspecialchars($data['siswa']->bb ?? '-'); ?> kg</td>
                                <td class="p-0"><?php cekLogEdit('tb', $data['log_edit']); ?></td>
                                <td class="p-0"><?php cekLogEdit('bb', $data['log_edit']); ?></td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-3">D. Keterangan Pendidikan</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">27.</td>
                                <td class="p-0">Asal SD</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->asal_sd ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('asal_sd', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">28.</td>
                                <td class="p-0">NPSN SD</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->npsn_sd ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">29.</td>
                                <td class="p-0">Pendidikan Sebelumnya</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->pend_sebelumnya ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Asal SMP</td>
                                <td class="p-0">:</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->asal_smp ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('asal_smp', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Alamat SMP</td>
                                <td class="p-0">:</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->alamat_smp ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('alamat_smp', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">NPSN SMP</td>
                                <td class="p-0">:</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->npsn_smp ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Nomor Seri Ijazah SMP</td>
                                <td class="p-0">:</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->seri_ijazah_smp ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Tanggal Ijazah SMP</td>
                                <td class="p-0">:</td>
                                <td class="p-0"><?= tanggal_indo($data['siswa']->tgl_ijazah_smp ?? ''); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Tahun Ijazah SMP</td>
                                <td class="p-0">:</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->th_ijazah_smp ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Lama Belajar SMP</td>
                                <td class="p-0">:</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->lama_belajar_smp ?? '-'); ?></td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-3">E. Keterangan Pendidikan</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">30.</td>
                                <td class="p-0">Diterima di Sekolah ini</td>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Tingkat</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->tingkat ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Bidang Keahlian</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->bid_keahlian ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Program Keahlian</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->prog_keahlian ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Konsentrasi Keahlian</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->jurusan ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Diterima Tanggal</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= tanggal_indo($data['siswa']->diterima_tgl ?? ''); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-3">F. Keterangan Ayah Kandung</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">31.</td>
                                <td class="p-0">Nama</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nama_ayah ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">32.</td>
                                <td class="p-0">NIK (Nomor Induk Kependudukan)</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nik_ayah ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">33.</td>
                                <td class="p-0">Tempat dan Tanggal Lahir</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->tmpt_lhr_ayah ?? '-'); ?>, <?= tanggal_indo($data['siswa']->tgl_lhr_ayah ?? ''); ?></td>
                                <td class="p-0"><?php cekLogEdit('tmpt_lhr_ayah', $data['log_edit']); ?></td>,<td class="p-0"><?php cekLogEdit('tgl_lhr_ayah', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">34.</td>
                                <td class="p-0">Agama</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->agama_ayah ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('agama_ayah', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">35.</td>
                                <td class="p-0">Kewarganegaraan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->kewarganegaraan_ayah ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('kewarganegaraan_ayah', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">36.</td>
                                <td class="p-0">Pendidikan Terakhir</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->pend_ayah ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('pend_ayah', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">37.</td>
                                <td class="p-0">Pekerjaan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->pekerjaan_ayah ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('pekerjaan_ayah', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">38.</td>
                                <td class="p-0">Penghasilan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0">Rp <?= number_format(is_numeric($data['siswa']->penghasilan_ayah) ? $data['siswa']->penghasilan_ayah : 0, 0, ',', '.'); ?></td>
                                <td class="p-0"><?php cekLogEdit('penghasilan_ayah', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">39.</td>
                                <td class="p-0">Alamat</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->alamat_ayah ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('alamat_ayah', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">40.</td>
                                <td class="p-0">Nomor Handphone</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->hp_ayah ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('hp_ayah', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">41.</td>
                                <td class="p-0">Masih Hidup / Meninggal Dunia</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->hidup_mati_ayah ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('hidup_mati_ayah', $data['log_edit']); ?></td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-3">G. Keterangan Ibu Kandung</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">42.</td>
                                <td class="p-0">Nama</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nama_ibu ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">43.</td>
                                <td class="p-0">NIK (Nomor Induk Kependudukan)</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nik_ibu ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">44.</td>
                                <td class="p-0">Tempat dan Tanggal Lahir</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->tmpt_lhr_ibu ?? '-'); ?>, <?= tanggal_indo($data['siswa']->tgl_lhr_ibu ?? ''); ?></td>
                                <td class="p-0"><?php cekLogEdit('tmpt_lhr_ibu', $data['log_edit']); ?></td> , <td class="p-0"><?php cekLogEdit('tgl_lhr_ibu', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">45.</td>
                                <td class="p-0">Agama</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->agama_ibu ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('agama_ibu', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">46.</td>
                                <td class="p-0">Kewarganegaraan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->kewarganegaraan_ibu ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('kewarganegaraan_ibu', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">47.</td>
                                <td class="p-0">Pendidikan Terakhir</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->pend_ibu ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('pend_ibu', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">48.</td>
                                <td class="p-0">Pekerjaan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->pekerjaan_ibu ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('pekerjaan_ibu', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">49.</td>
                                <td class="p-0">Penghasilan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0">Rp <?= number_format(is_numeric($data['siswa']->penghasilan_ibu) ? $data['siswa']->penghasilan_ibu : 0, 0, ',', '.'); ?></td>
                                <td class="p-0"><?php cekLogEdit('penghasilan_ibu', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">50.</td>
                                <td class="p-0">Alamat</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->alamat_ibu ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('alamat_ibu', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">51.</td>
                                <td class="p-0">Nomor Handphone</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->hp_ibu ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('hp_ibu', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">52.</td>
                                <td class="p-0">Masih Hidup / Meninggal Dunia</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->hidup_mati_ibu ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('hidup_mati_ibu', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-3">H. Keterangan Wali</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">53.</td>
                                <td class="p-0">Nama</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nama_wali ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('nama_wali', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">54.</td>
                                <td class="p-0">NIK (Nomor Induk Kependudukan)</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nik_wali ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('nik_wali', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">55.</td>
                                <td class="p-0">Tempat dan Tanggal Lahir</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->tmpt_lhr_wali ?? '-'); ?>, <?= tanggal_indo($data['siswa']->tgl_lhr_wali ?? ''); ?></td>
                                <td class="p-0"><?php cekLogEdit('tgl_lhr_wali', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">56.</td>
                                <td class="p-0">Agama</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->agama_wali ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('agama_wali', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">57.</td>
                                <td class="p-0">Kewarganegaraan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->kewarganegaraan_wali ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('kewarganegaraan_wali', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">58.</td>
                                <td class="p-0">Pendidikan Terakhir</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->pend_wali ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('pend_wali', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">59.</td>
                                <td class="p-0">Pekerjaan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->pekerjaan_wali ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('pekerjaan_wali', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">60.</td>
                                <td class="p-0">Penghasilan</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0">Rp <?= number_format(is_numeric($data['siswa']->penghasilan_wali) ? $data['siswa']->penghasilan_wali : 0, 0, ',', '.'); ?></td>
                                <td class="p-0"><?php cekLogEdit('penghasilan_wali', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">61.</td>
                                <td class="p-0">Alamat</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->alamat_wali ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('alamat_wali', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">62.</td>
                                <td class="p-0">Nomor Handphone</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->hp_wali ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('hp_wali', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-3">I. Kegemaran / Hobi</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">63.</td>
                                <td class="p-0">Kesenian</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->kesenian ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('kesenian', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">64.</td>
                                <td class="p-0">Olah Raga</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->olahraga ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('olahraga', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">65.</td>
                                <td class="p-0">Organisasi</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->organisasi ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('organisasi', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">66.</td>
                                <td class="p-0">Cita-cita</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->cita_cita ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('cita_cita', $data['log_edit']); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0">67.</td>
                                <td class="p-0">Lain-lain</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->lain_lain ?? '-'); ?></td>
                                <td class="p-0"><?php cekLogEdit('lain_lain', $data['log_edit']); ?></td>
                            </tr>

                            <tr>
                                <td colspan="5">
                                    <h4 class="mt-3">J. Status</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Status Siswa</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><span class="badge bg-success"><?= htmlspecialchars($data['siswa']->status ?? '-'); ?></span></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Rombongan Belajar</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= htmlspecialchars($data['siswa']->nama_rombel ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Data Dibuat Pada</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= tanggal_indo($data['siswa']->created_at_induk ?? '', true); ?></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0">Data Diperbarui Pada</td>
                                <td class="p-0">:&nbsp;</td>
                                <td class="p-0"><?= tanggal_indo($data['siswa']->updated_at_induk ?? '', true); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
<?php require_once '../app/views/templates/footer.php'; ?>