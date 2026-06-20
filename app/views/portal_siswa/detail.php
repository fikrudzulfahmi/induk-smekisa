<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['judul']); ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-color: #f2f7ff;
            --card-bg: #ffffff;
            --primary: #435ebe;
            --primary-hover: #394fa3;
            --text-dark: #25396f;
            --text-muted: #7c8db5;
            --border-color: #edf2f7;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            padding: 30px 15px;
        }

        .container {
            max-width: 650px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-back {
            background-color: var(--card-bg);
            color: var(--primary);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: var(--primary);
            color: #fff;
        }

        .header-text h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .header-text p {
            color: var(--text-muted);
            font-size: 13px;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-body {
            padding: 20px;
        }

        .card-header-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary);
            padding-left: 10px;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .profile-summary {
            text-align: center;
            padding: 10px 0;
        }

        .avatar-large {
            width: 80px;
            height: 80px;
            background-color: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            margin: 0 auto 15px auto;
            box-shadow: 0 4px 12px rgba(67, 94, 190, 0.2);
        }

        .student-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-dark);
        }

        .student-meta {
            color: var(--text-muted);
            font-size: 13px;
        }

        .detail-list {
            list-style: none;
        }

        .detail-item {
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
            display: flex;
            flex-direction: column;
        }

        .detail-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-label {
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .detail-value {
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.5;
        }

        .sub-value {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 400;
            margin-top: 3px;
        }

        /* Gaya dasar tombol */
        .btn-edit-custom {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #0d6efd;
            /* Warna biru modern */
            color: #ffffff !important;
            /* Warna teks putih */
            padding: 10px 24px;
            border-radius: 8px;
            /* Ujung membulat */
            font-weight: 600;
            text-decoration: none;
            /* Menghilangkan garis bawah */
            font-size: 15px;
            transition: all 0.3s ease;
            /* Transisi halus */
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.2);
            /* Bayangan lembut */
            border: 1px solid transparent;
        }

        /* Efek saat kursor diarahkan (Hover) */
        .btn-edit-custom:hover {
            background-color: #0b5ed7;
            /* Warna biru lebih gelap */
            transform: translateY(-2px);
            /* Efek tombol terangkat */
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
            /* Bayangan membesar */
            color: #ffffff;
        }

        /* Efek saat tombol diklik (Active) */
        .btn-edit-custom:active {
            transform: translateY(0);
            /* Tombol turun kembali */
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.2);
        }
    </style>
</head>

<body>
    <?php
    $mutasi_jenis = $data['mutasi']['jenis'] ?? '-';
    $mutasi_tgl = $data['mutasi']['tanggal'] ?? '-';
    $mutasi_from = $data['mutasi']['asal'] ?? '-';
    $mutasi_to = $data['mutasi']['tujuan'] ?? '-';
    $mutasi_alasan = $data['mutasi']['alasan'] ?? '-';
    ?>
    <div class="container">
        <div class="page-header">
            <a href="<?= BASEURL; ?>/portal_siswa" class="btn-back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div class="header-text">
                <h3>Detail Data Siswa</h3>
                <p>Buku Induk Siswa Lengkap</p>
            </div>
        </div>

        <?php if ($data['siswa']) : ?>

            <div class="card">
                <div class="card-body profile-summary">
                    <div class="avatar-large">
                        <?= strtoupper(substr($data['siswa']->nama_siswa ?? 'S', 0, 1)); ?>
                    </div>
                    <div class="student-name">
                        <?= htmlspecialchars(strtoupper($data['siswa']->nama_siswa ?? '-')); ?>
                        <?= !empty($data['siswa']->nama_panggilan) ? ' ("' . htmlspecialchars($data['siswa']->nama_panggilan) . '")' : ''; ?>
                    </div>
                    <div class="student-meta">
                        No. Induk: <?= htmlspecialchars($data['siswa']->no_induk ?? '-'); ?> | NISN: <?= htmlspecialchars($data['siswa']->nisn ?? '-'); ?>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="display: flex; justify-content: center;">
                    <a href="<?= BASEURL; ?>/portal_siswa/edit" class="btn-edit-custom">
                        <i class="bi bi-pencil-square"></i> Edit Data Siswa
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-header-title">
                        <i class="bi bi-person-badge"></i> A. Keterangan Diri Siswa
                    </div>
                    <ul class="detail-list">
                        <li class="detail-item">
                            <span class="detail-label">Identitas Kependudukan</span>
                            <span class="detail-value">
                                NIK: <?= htmlspecialchars($data['siswa']->nik ?? '-'); ?><br>
                                NKK: <?= htmlspecialchars($data['siswa']->nkk ?? '-'); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Jenis Kelamin</span>
                            <span class="detail-value"><?= htmlspecialchars($data['siswa']->jenis_kelamin ?? '-'); ?></span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Tempat, Tanggal Lahir</span>
                            <span class="detail-value">
                                <?= htmlspecialchars($data['siswa']->tmpt_lhr ?? '-'); ?>, <?= tanggal_indo($data['siswa']->tgl_lhr ?? ''); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Agama & Kewarganegaraan</span>
                            <span class="detail-value">
                                <?= htmlspecialchars($data['siswa']->agama ?? '-'); ?> | <?= htmlspecialchars($data['siswa']->kewarganegaraan ?? '-'); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Data Keluarga</span>
                            <span class="detail-value">Anak ke-<?= htmlspecialchars($data['siswa']->anak_ke ?? '-'); ?></span>
                            <span class="sub-value">
                                Sdr. Kandung: <?= htmlspecialchars($data['siswa']->jml_sdr_kandung ?? '0'); ?> |
                                Sdr. Tiri: <?= htmlspecialchars($data['siswa']->jml_sdr_tiri ?? '0'); ?> |
                                Sdr. Angkat: <?= htmlspecialchars($data['siswa']->jml_sdr_angkat ?? '0'); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Status Anak & Bahasa</span>
                            <span class="detail-value">
                                <?= htmlspecialchars($data['siswa']->yatim_piatu ?? '-'); ?> | Bahasa: <?= htmlspecialchars($data['siswa']->bahasa ?? '-'); ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-header-title">
                        <i class="bi bi-house-door-fill"></i> B. Keterangan Tempat Tinggal
                    </div>
                    <ul class="detail-list">
                        <li class="detail-item">
                            <span class="detail-label">Alamat Lengkap</span>
                            <span class="detail-value">
                                <?= htmlspecialchars($data['siswa']->alamat ?? ''); ?>,
                                Dusun <?= htmlspecialchars($data['siswa']->dusun ?? '-'); ?> RT <?= htmlspecialchars($data['siswa']->rt ?? '-'); ?>/RW <?= htmlspecialchars($data['siswa']->rw ?? '-'); ?>,<br>
                                Desa/Kel <?= htmlspecialchars($data['siswa']->desa ?? '-'); ?>, Kec. <?= htmlspecialchars($data['siswa']->kec ?? '-'); ?>,<br>
                                <?= htmlspecialchars($data['siswa']->kab ?? '-'); ?> - <?= htmlspecialchars($data['siswa']->kd_pos ?? '-'); ?>, Prov. <?= htmlspecialchars($data['siswa']->provinsi ?? '-'); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Kontak</span>
                            <span class="detail-value">
                                HP: <?= htmlspecialchars($data['siswa']->no_hp ?? '-'); ?> <br>
                                Telp Rumah: <?= htmlspecialchars($data['siswa']->no_tlp ?? '-'); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Tempat Tinggal & Transportasi</span>
                            <span class="detail-value">Tinggal bersama: <?= htmlspecialchars($data['siswa']->tinggal_bersama ?? '-'); ?></span>
                            <span class="sub-value">Transportasi: <?= htmlspecialchars($data['siswa']->transportasi ?? '-'); ?></span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Jarak & Waktu Tempuh</span>
                            <span class="detail-value">
                                <?= htmlspecialchars($data['siswa']->jarak_rumah ?? '-'); ?> km (± <?= htmlspecialchars($data['siswa']->wkt_tempuh ?? '-'); ?> menit)
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-header-title">
                        <i class="bi bi-heart-pulse-fill"></i> C. Keterangan Kesehatan
                    </div>
                    <ul class="detail-list">
                        <li class="detail-item">
                            <span class="detail-label">Golongan Darah</span>
                            <span class="detail-value"><?= htmlspecialchars($data['siswa']->gol_darah ?? '-'); ?></span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Tinggi / Berat Badan</span>
                            <span class="detail-value"><?= htmlspecialchars($data['siswa']->tb ?? '-'); ?> cm / <?= htmlspecialchars($data['siswa']->bb ?? '-'); ?> kg</span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Riwayat Penyakit & Kelainan</span>
                            <span class="detail-value">
                                Penyakit: <?= htmlspecialchars($data['siswa']->penyakit ?? '-'); ?><br>
                                Kelainan: <?= htmlspecialchars($data['siswa']->kelainan_jasmani ?? '-'); ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-header-title">
                        <i class="bi bi-clock-history"></i> D. Pendidikan Sebelumnya
                    </div>
                    <ul class="detail-list">
                        <li class="detail-item">
                            <span class="detail-label">Riwayat SD</span>
                            <span class="detail-value">Asal SD: <?= htmlspecialchars($data['siswa']->asal_sd ?? '-'); ?></span>
                            <span class="sub-value">NPSN SD: <?= htmlspecialchars($data['siswa']->npsn_sd ?? '-'); ?></span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Riwayat SMP</span>
                            <span class="detail-value">
                                Asal SMP: <?= htmlspecialchars($data['siswa']->asal_smp ?? '-'); ?> <br>
                                NPSN SMP: <?= htmlspecialchars($data['siswa']->npsn_smp ?? '-'); ?>
                            </span>
                            <span class="sub-value">Alamat SMP: <?= htmlspecialchars($data['siswa']->alamat_smp ?? '-'); ?></span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Ijazah SMP & Pendidikan Lain</span>
                            <span class="detail-value">
                                No. Seri Ijazah: <?= htmlspecialchars($data['siswa']->seri_ijazah_smp ?? '-'); ?><br>
                                Tgl/Tahun: <?= tanggal_indo($data['siswa']->tgl_ijazah_smp ?? ''); ?> / <?= htmlspecialchars($data['siswa']->th_ijazah_smp ?? '-'); ?>
                            </span>
                            <span class="sub-value">Lama Belajar: <?= htmlspecialchars($data['siswa']->lama_belajar_smp ?? '-'); ?> | Pend. Sebelumnya: <?= htmlspecialchars($data['siswa']->pend_sebelumnya ?? '-'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-header-title">
                        <i class="bi bi-building-check"></i> E. Diterima di Sekolah Ini
                    </div>
                    <ul class="detail-list">
                        <li class="detail-item">
                            <span class="detail-label">Tanggal Diterima</span>
                            <span class="detail-value"><?= tanggal_indo($data['siswa']->diterima_tgl ?? ''); ?></span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Tingkat & Penjurusan</span>
                            <span class="detail-value">
                                Tingkat: <?= htmlspecialchars($data['siswa']->tingkat ?? '-'); ?><br>
                                Bidang Keahlian: <?= htmlspecialchars($data['siswa']->bid_keahlian ?? '-'); ?><br>
                                Program Keahlian: <?= htmlspecialchars($data['siswa']->prog_keahlian ?? '-'); ?><br>
                                Konsentrasi Keahlian: <?= htmlspecialchars($data['siswa']->jurusan ?? '-'); ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-header-title">
                        <i class="bi bi-people-fill"></i> F. Keterangan Orang Tua Kandung
                    </div>
                    <ul class="detail-list">
                        <li class="detail-item">
                            <span class="detail-label">Data Ayah Kandung</span>
                            <span class="detail-value">
                                Nama: <?= htmlspecialchars($data['siswa']->nama_ayah ?? '-'); ?><br>
                                NIK: <?= htmlspecialchars($data['siswa']->nik_ayah ?? '-'); ?>
                            </span>
                            <span class="sub-value">
                                Tempat, Tgl Lahir: <?= htmlspecialchars($data['siswa']->tmpt_lhr_ayah ?? '-'); ?>, <?= tanggal_indo($data['siswa']->tgl_lhr_ayah ?? ''); ?>
                            </span>
                            <span class="sub-value">
                                <?= htmlspecialchars($data['siswa']->hidup_mati_ayah ?? '-'); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Latar Belakang Ayah</span>
                            <span class="detail-value">
                                Agama: <?= htmlspecialchars($data['siswa']->agama_ayah ?? '-'); ?> | Pend. Terakhir: <?= htmlspecialchars($data['siswa']->pend_ayah ?? '-'); ?>
                            </span>
                            <span class="sub-value">
                                Pekerjaan: <?= htmlspecialchars($data['siswa']->pekerjaan_ayah ?? '-'); ?><br>
                                Penghasilan/Bulan: Rp <?= number_format(is_numeric($data['siswa']->penghasilan_ayah) ? $data['siswa']->penghasilan_ayah : 0, 0, ',', '.'); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Kontak & Alamat Ayah</span>
                            <span class="detail-value">No. HP: <?= htmlspecialchars($data['siswa']->hp_ayah ?? '-'); ?></span>
                            <span class="sub-value">Alamat: <?= htmlspecialchars($data['siswa']->alamat_ayah ?? '-'); ?></span>
                        </li>

                        <li class="detail-item" style="margin-top: 10px; border-top: 2px dashed var(--border-color); padding-top: 15px;">
                            <span class="detail-label">Data Ibu Kandung</span>
                            <span class="detail-value">
                                Nama: <?= htmlspecialchars($data['siswa']->nama_ibu ?? '-'); ?><br>
                                NIK: <?= htmlspecialchars($data['siswa']->nik_ibu ?? '-'); ?>
                            </span>
                            <span class="sub-value">
                                Tempat, Tgl Lahir: <?= htmlspecialchars($data['siswa']->tmpt_lhr_ibu ?? '-'); ?>, <?= tanggal_indo($data['siswa']->tgl_lhr_ibu ?? ''); ?>
                            </span>
                            <span class="sub-value">
                                <?= htmlspecialchars($data['siswa']->hidup_mati_ibu ?? '-'); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Latar Belakang Ibu</span>
                            <span class="detail-value">
                                Agama: <?= htmlspecialchars($data['siswa']->agama_ibu ?? '-'); ?> | Pend. Terakhir: <?= htmlspecialchars($data['siswa']->pend_ibu ?? '-'); ?>
                            </span>
                            <span class="sub-value">
                                Pekerjaan: <?= htmlspecialchars($data['siswa']->pekerjaan_ibu ?? '-'); ?><br>
                                Penghasilan/Bulan: Rp <?= number_format(is_numeric($data['siswa']->penghasilan_ibu) ? $data['siswa']->penghasilan_ibu : 0, 0, ',', '.'); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Kontak & Alamat Ibu</span>
                            <span class="detail-value">No. HP: <?= htmlspecialchars($data['siswa']->hp_ibu ?? '-'); ?></span>
                            <span class="sub-value">Alamat: <?= htmlspecialchars($data['siswa']->alamat_ibu ?? '-'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-header-title">
                        <i class="bi bi-person-bounding-box"></i> G. Keterangan Wali
                    </div>
                    <ul class="detail-list">
                        <li class="detail-item">
                            <span class="detail-label">Identitas Wali</span>
                            <span class="detail-value">Nama Wali: <?= htmlspecialchars($data['siswa']->nama_wali ?? '-'); ?></span>
                            <span class="sub-value">Hubungan Keluarga: <?= htmlspecialchars($data['siswa']->hub_wali ?? '-'); ?></span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Latar Belakang Wali</span>
                            <span class="detail-value">Pend. Terakhir: <?= htmlspecialchars($data['siswa']->pend_wali ?? '-'); ?></span>
                            <span class="sub-value">
                                Pekerjaan: <?= htmlspecialchars($data['siswa']->pekerjaan_wali ?? '-'); ?><br>
                                Penghasilan/Bulan: Rp <?= number_format(is_numeric($data['siswa']->penghasilan_wali) ? $data['siswa']->penghasilan_wali : 0, 0, ',', '.'); ?>
                            </span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Kontak & Alamat Wali</span>
                            <span class="detail-value">No. HP: <?= htmlspecialchars($data['siswa']->hp_wali ?? '-'); ?></span>
                            <span class="sub-value">Alamat: <?= htmlspecialchars($data['siswa']->alamat_wali ?? '-'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-header-title">
                        <i class="bi bi-mortarboard-fill"></i> H. Perkembangan & Lulus/Keluar
                    </div>
                    <ul class="detail-list">
                        <li class="detail-item">
                            <span class="detail-label">Bakat & Hobi</span>
                            <span class="detail-value">
                                Kesenian: <?= htmlspecialchars($data['siswa']->kesenian ?? '-'); ?><br>
                                Olahraga: <?= htmlspecialchars($data['siswa']->olahraga ?? '-'); ?>
                            </span>
                            <span class="sub-value">Organisasi/Lainnya: <?= htmlspecialchars($data['siswa']->organisasi ?? '-'); ?></span>
                        </li>
                        <li class="detail-item">
                            <span class="detail-label">Keterangan Meninggalkan Sekolah</span>
                            <?php if (isset($data['siswa']->id_status) && $data['siswa']->id_status == 4) : ?>
                                <span class="detail-value">Status: LULUS</span>
                                <span class="sub-value">
                                    Tahun Kelulusan: <?= htmlspecialchars($data['siswa']->tahun_lulus ?? '-'); ?>
                                </span>
                            <?php else : ?>
                                <span class="detail-value">Status: Masih Aktif / Belum Lulus</span>
                            <?php endif; ?>
                        </li>

                        <li class="detail-item">
                            <span class="detail-label">Riwayat Mutasi Masuk / Keluar</span>
                            <?php if (!empty($mutasi_jenis) && $mutasi_jenis !== '-') : ?>
                                <span class="detail-value">
                                    Jenis Mutasi: <?= htmlspecialchars($mutasi_jenis); ?>
                                </span>
                                <span class="sub-value">
                                    Tanggal Mutasi: <?= !empty($mutasi_tgl) ? tanggal_indo($mutasi_tgl) : '-'; ?><br>
                                    Pindahan dari: <?= ($mutasi_jenis == 'Mutasi Keluar') ? 'SMK Islam 1 Blitar' : htmlspecialchars($mutasi_from ?? '-'); ?><br>
                                    Sekolah tujuan: <?= ($mutasi_jenis == 'Mutasi Masuk') ? 'SMK Islam 1 Blitar' : htmlspecialchars($mutasi_to ?? '-'); ?><br>
                                    Alasan: <?= htmlspecialchars($mutasi_alasan ?? '-'); ?>
                                </span>
                            <?php else : ?>
                                <span class="detail-value" style="color: var(--text-muted); font-weight: normal;">
                                    Tidak ada catatan mutasi.
                                </span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>

        <?php else : ?>

            <div class="card">
                <div class="card-body" style="text-align: center; padding: 40px 20px;">
                    <i class="bi bi-exclamation-circle text-muted" style="font-size: 40px; margin-bottom: 15px; display: block;"></i>
                    <h4 style="color: var(--text-dark); margin-bottom: 10px;">Data Tidak Ditemukan</h4>
                    <p style="color: var(--text-muted); font-size: 14px;">Maaf, data siswa yang Anda cari tidak tersedia atau mungkin telah dihapus.</p>
                </div>
            </div>

        <?php endif; ?>

    </div>
</body>

</html>