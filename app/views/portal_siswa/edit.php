<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Lengkap Siswa</title>
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
            --input-bg: #f8fafc;
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
            max-width: 800px;
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
            padding: 25px;
        }

        .card-header-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary);
            padding-left: 10px;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-divider {
            grid-column: 1 / -1;
            font-weight: 700;
            font-size: 14px;
            color: var(--primary);
            border-bottom: 2px dashed var(--border-color);
            padding-bottom: 5px;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            font-family: 'Nunito', sans-serif;
            font-size: 14px;
            color: var(--text-dark);
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(67, 94, 190, 0.1);
        }

        .btn-submit {
            background-color: #198754;
            color: #fff;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            font-family: 'Nunito', sans-serif;
            font-size: 16px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 40px;
        }

        .btn-submit:hover {
            background-color: #157347;
        }

        /* 1. Gaya untuk input yang BISA DIEDIT (Normal) */
        .form-control {
            background-color: #ffffff !important;
            /* Latar putih bersih menandakan bisa diisi */
            border: 1px solid #d1d5db;
            /* Border abu-abu standar */
            color: #1f2937;
            /* Teks warna gelap pekat */
            transition: all 0.3s ease;
        }

        /* Efek saat input yang bisa diedit sedang diklik/fokus */
        .form-control:focus {
            background-color: #ffffff !important;
            border-color: #0d6efd;
            /* Border biru */
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* 2. Gaya untuk input yang TIDAK BISA DIEDIT (Disabled / Readonly) */
        .form-control:disabled,
        .form-control[readonly] {
            background-color: #f3f4f6 !important;
            /* Latar abu-abu lembut */
            color: #6b7280 !important;
            /* Teks agak pudar/samar */
            border: 1px solid #e5e7eb !important;
            /* Border lebih tipis/pudar */
            cursor: not-allowed !important;
            /* Kursor berubah jadi tanda dilarang (bulat coret) */
            opacity: 1;
            /* Mencegah iOS Safari memudarkan warna terlalu ekstrem */
        }

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="page-header">
            <a href="<?= BASEURL; ?>/portal_siswa/detail" class="btn-back"><i class="bi bi-arrow-left"></i></a>
            <div class="header-text">
                <h3>Edit Data Lengkap Siswa</h3>
                <p>Perbarui semua informasi siswa dari Bagian A sampai H</p>
            </div>
        </div>

        <?php if ($data['siswa']) :
            // Inisialisasi variabel mutasi agar tidak error jika array kosong
            $mutasi_jenis = $data['mutasi']['jenis'] ?? '';
            $mutasi_tgl = $data['mutasi']['tanggal'] ?? '';
            $mutasi_from = $data['mutasi']['asal'] ?? '';
            $mutasi_to = $data['mutasi']['tujuan'] ?? '';
            $mutasi_alasan = $data['mutasi']['alasan'] ?? '';
        ?>
            <form action="<?= BASEURL; ?>/portal_siswa/update" method="POST" onsubmit="document.getElementById('btn-simpan').disabled = true; document.getElementById('btn-simpan').innerHTML = 'Menyimpan...';">
                <input type="hidden" name="id" value="<?= htmlspecialchars($data['siswa']->id ?? ''); ?>">

                <div class="card">
                    <div class="card-body">
                        <div class="card-header-title"><i class="bi bi-person-badge"></i> A. Keterangan Diri Siswa</div>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label class="form-label">Nama Lengkap Siswa</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->nama_siswa ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Panggilan</label>
                                <input type="text" class="form-control" name="nama_panggilan" value="<?= htmlspecialchars($data['siswa']->nama_panggilan ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jenis Kelamin</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->jenis_kelamin ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. Induk</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->no_induk ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">NISN</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->nisn ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">NIK</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->nik ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">NKK (No. Kartu Keluarga)</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->nkk ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" name="tmpt_lhr" value="<?= htmlspecialchars($data['siswa']->tmpt_lhr ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tgl_lhr" value="<?= htmlspecialchars($data['siswa']->tgl_lhr ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Agama</label>
                                <input type="text" class="form-control" name="agama" value="<?= htmlspecialchars($data['siswa']->agama ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kewarganegaraan</label>
                                <input type="text" class="form-control" name="kewarganegaraan" value="<?= htmlspecialchars($data['siswa']->kewarganegaraan ?? '-'); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Anak Ke-</label>
                                <input type="text" class="form-control" name="anak_ke" value="<?= htmlspecialchars($data['siswa']->anak_ke ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jumlah Sdr. Kandung</label>
                                <input type="text" class="form-control" name="jml_sdr_kandung" value="<?= htmlspecialchars($data['siswa']->jml_sdr_kandung ?? '0'); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jumlah Sdr. Tiri</label>
                                <input type="text" class="form-control" name="jml_sdr_tiri" value="<?= htmlspecialchars($data['siswa']->jml_sdr_tiri ?? '0'); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jumlah Sdr. Angkat</label>
                                <input type="text" class="form-control" name="jml_sdr_angkat" value="<?= htmlspecialchars($data['siswa']->jml_sdr_angkat ?? '0'); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status Anak (Yatim/Piatu dll)</label>
                                <input type="text" class="form-control" name="yatim_piatu" value="<?= htmlspecialchars($data['siswa']->yatim_piatu ?? '-'); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bahasa Sehari-hari</label>
                                <input type="text" class="form-control" name="bahasa" value="<?= htmlspecialchars($data['siswa']->bahasa ?? '-'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-header-title"><i class="bi bi-house-door-fill"></i> B. Tempat Tinggal</div>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label class="form-label">Alamat Jalan</label>
                                <input type="text" class="form-control" name="alamat" value="<?= htmlspecialchars($data['siswa']->alamat ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Dusun</label>
                                <input type="text" class="form-control" name="dusun" value="<?= htmlspecialchars($data['siswa']->dusun ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">RT</label>
                                <input type="text" class="form-control" name="rt" value="<?= htmlspecialchars($data['siswa']->rt ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">RW</label>
                                <input type="text" class="form-control" name="rw" value="<?= htmlspecialchars($data['siswa']->rw ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Desa / Kelurahan</label>
                                <input type="text" class="form-control" name="desa" value="<?= htmlspecialchars($data['siswa']->desa ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kecamatan</label>
                                <input type="text" class="form-control" name="kec" value="<?= htmlspecialchars($data['siswa']->kec ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kabupaten / Kota</label>
                                <input type="text" class="form-control" name="kab" value="<?= htmlspecialchars($data['siswa']->kab ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Provinsi</label>
                                <input type="text" class="form-control" name="provinsi" value="<?= htmlspecialchars($data['siswa']->provinsi ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" name="kd_pos" value="<?= htmlspecialchars($data['siswa']->kd_pos ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. HP</label>
                                <input type="text" class="form-control" name="no_hp" value="<?= htmlspecialchars($data['siswa']->no_hp ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Telepon Rumah</label>
                                <input type="text" class="form-control" name="no_tlp" value="<?= htmlspecialchars($data['siswa']->no_tlp ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tinggal Bersama</label>
                                <input type="text" class="form-control" name="tinggal_bersama" value="<?= htmlspecialchars($data['siswa']->tinggal_bersama ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Transportasi</label>
                                <input type="text" class="form-control" name="transportasi" value="<?= htmlspecialchars($data['siswa']->transportasi ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jarak Rumah (km)</label>
                                <input type="text" class="form-control" name="jarak_rumah" value="<?= htmlspecialchars($data['siswa']->jarak_rumah ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Waktu Tempuh (menit)</label>
                                <input type="text" class="form-control" name="wkt_tempuh" value="<?= htmlspecialchars($data['siswa']->wkt_tempuh ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-header-title"><i class="bi bi-heart-pulse-fill"></i> C. Kesehatan</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Golongan Darah</label>
                                <input type="text" class="form-control" name="gol_darah" value="<?= htmlspecialchars($data['siswa']->gol_darah ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tinggi Badan (cm)</label>
                                <input type="text" class="form-control" name="tb" value="<?= htmlspecialchars($data['siswa']->tb ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Berat Badan (kg)</label>
                                <input type="text" class="form-control" name="bb" value="<?= htmlspecialchars($data['siswa']->bb ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Riwayat Penyakit</label>
                                <input type="text" class="form-control" name="penyakit" value="<?= htmlspecialchars($data['siswa']->penyakit ?? ''); ?>">
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Kelainan Jasmani</label>
                                <input type="text" class="form-control" name="kelainan_jasmani" value="<?= htmlspecialchars($data['siswa']->kelainan_jasmani ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-header-title"><i class="bi bi-clock-history"></i> D. Pendidikan Sebelumnya</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Asal SD</label>
                                <input type="text" class="form-control" name="asal_sd" value="<?= htmlspecialchars($data['siswa']->asal_sd ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">NPSN SD</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->npsn_sd ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Asal SMP</label>
                                <input type="text" class="form-control" name="asal_smp" value="<?= htmlspecialchars($data['siswa']->asal_smp ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">NPSN SMP</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->npsn_smp ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Alamat SMP</label>
                                <input type="text" class="form-control" name="alamat_smp" value="<?= htmlspecialchars($data['siswa']->alamat_smp ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. Seri Ijazah SMP</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->seri_ijazah_smp ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal Ijazah SMP</label>
                                <input type="date" class="form-control" value="<?= htmlspecialchars($data['siswa']->tgl_ijazah_smp ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tahun Ijazah SMP</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->th_ijazah_smp ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Lama Belajar SMP (Tahun)</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->lama_belajar_smp ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Pendidikan Sebelum SMP (Jika Ada)</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->pend_sebelumnya ?? ''); ?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-header-title"><i class="bi bi-building-check"></i> E. Diterima di Sekolah Ini</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Tanggal Diterima</label>
                                <input type="date" class="form-control" value="<?= htmlspecialchars($data['siswa']->diterima_tgl ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tingkat</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->tingkat ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bidang Keahlian</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->bid_keahlian ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Program Keahlian</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->prog_keahlian ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Konsentrasi Keahlian (Jurusan)</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->jurusan ?? ''); ?>" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-header-title"><i class="bi bi-people-fill"></i> F. Keterangan Orang Tua</div>
                        <div class="form-grid">
                            <div class="section-divider">Data Ayah Kandung</div>
                            <div class="form-group">
                                <label class="form-label">Nama Ayah</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->nama_ayah ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">NIK Ayah</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->nik_ayah ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tempat Lahir Ayah</label>
                                <input type="text" class="form-control" name="tmpt_lhr_ayah" value="<?= htmlspecialchars($data['siswa']->tmpt_lhr_ayah ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal Lahir Ayah</label>
                                <input type="date" class="form-control" name="tgl_lhr_ayah" value="<?= htmlspecialchars($data['siswa']->tgl_lhr_ayah ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status Hidup Ayah</label>
                                <input type="text" class="form-control" name="hidup_mati_ayah" value="<?= htmlspecialchars($data['siswa']->hidup_mati_ayah ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Agama Ayah</label>
                                <input type="text" class="form-control" name="agama_ayah" value="<?= htmlspecialchars($data['siswa']->agama_ayah ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pendidikan Terakhir Ayah</label>
                                <input type="text" class="form-control" name="pend_ayah" value="<?= htmlspecialchars($data['siswa']->pend_ayah ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pekerjaan Ayah</label>
                                <input type="text" class="form-control" name="pekerjaan_ayah" value="<?= htmlspecialchars($data['siswa']->pekerjaan_ayah ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Penghasilan/Bulan Ayah</label>
                                <input type="text" class="form-control" name="penghasilan_ayah" value="<?= htmlspecialchars($data['siswa']->penghasilan_ayah ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. HP Ayah</label>
                                <input type="text" class="form-control" name="hp_ayah" value="<?= htmlspecialchars($data['siswa']->hp_ayah ?? ''); ?>">
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Alamat Lengkap Ayah</label>
                                <input type="text" class="form-control" name="alamat_ayah" value="<?= htmlspecialchars($data['siswa']->alamat_ayah ?? ''); ?>">
                            </div>

                            <div class="section-divider">Data Ibu Kandung</div>
                            <div class="form-group">
                                <label class="form-label">Nama Ibu</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->nama_ibu ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">NIK Ibu</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->nik_ibu ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tempat Lahir Ibu</label>
                                <input type="text" class="form-control" name="tmpt_lhr_ibu" value="<?= htmlspecialchars($data['siswa']->tmpt_lhr_ibu ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tanggal Lahir Ibu</label>
                                <input type="date" class="form-control" name="tgl_lhr_ibu" value="<?= htmlspecialchars($data['siswa']->tgl_lhr_ibu ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status Hidup Ibu</label>
                                <input type="text" class="form-control" name="hidup_mati_ibu" value="<?= htmlspecialchars($data['siswa']->hidup_mati_ibu ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Agama Ibu</label>
                                <input type="text" class="form-control" name="agama_ibu" value="<?= htmlspecialchars($data['siswa']->agama_ibu ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pendidikan Terakhir Ibu</label>
                                <input type="text" class="form-control" name="pend_ibu" value="<?= htmlspecialchars($data['siswa']->pend_ibu ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pekerjaan Ibu</label>
                                <input type="text" class="form-control" name="pekerjaan_ibu" value="<?= htmlspecialchars($data['siswa']->pekerjaan_ibu ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Penghasilan/Bulan Ibu</label>
                                <input type="text" class="form-control" name="penghasilan_ibu" value="<?= htmlspecialchars($data['siswa']->penghasilan_ibu ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. HP Ibu</label>
                                <input type="text" class="form-control" name="hp_ibu" value="<?= htmlspecialchars($data['siswa']->hp_ibu ?? ''); ?>">
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Alamat Lengkap Ibu</label>
                                <input type="text" class="form-control" name="alamat_ibu" value="<?= htmlspecialchars($data['siswa']->alamat_ibu ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-header-title"><i class="bi bi-person-bounding-box"></i> G. Keterangan Wali</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nama Wali</label>
                                <input type="text" class="form-control" name="nama_wali" value="<?= htmlspecialchars($data['siswa']->nama_wali ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pendidikan Terakhir Wali</label>
                                <input type="text" class="form-control" name="pend_wali" value="<?= htmlspecialchars($data['siswa']->pend_wali ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pekerjaan Wali</label>
                                <input type="text" class="form-control" name="pekerjaan_wali" value="<?= htmlspecialchars($data['siswa']->pekerjaan_wali ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Penghasilan/Bulan Wali</label>
                                <input type="text" class="form-control" name="penghasilan_wali" value="<?= htmlspecialchars($data['siswa']->penghasilan_wali ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">No. HP Wali</label>
                                <input type="text" class="form-control" name="hp_wali" value="<?= htmlspecialchars($data['siswa']->hp_wali ?? ''); ?>">
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Alamat Lengkap Wali</label>
                                <input type="text" class="form-control" name="alamat_wali" value="<?= htmlspecialchars($data['siswa']->alamat_wali ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="card-header-title"><i class="bi bi-mortarboard-fill"></i> H. Perkembangan & Lulus/Mutasi</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Bakat Kesenian</label>
                                <input type="text" class="form-control" name="kesenian" value="<?= htmlspecialchars($data['siswa']->kesenian ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bakat Olahraga</label>
                                <input type="text" class="form-control" name="olahraga" value="<?= htmlspecialchars($data['siswa']->olahraga ?? ''); ?>">
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Pengalaman Organisasi / Lainnya</label>
                                <input type="text" class="form-control" name="organisasi" value="<?= htmlspecialchars($data['siswa']->organisasi ?? ''); ?>">
                            </div>

                            <div class="section-divider">Status Kelulusan</div>
                            <?php if (isset($data['siswa']->id_status) && $data['siswa']->id_status == 4) : ?>
                                <div class="form-group">
                                    <label class="form-label">Status Kelulusan</label>
                                    <input type="text" class="form-control" value="Lulus" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tahun Kelulusan</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($data['siswa']->tahun_lulus ?? ''); ?>" disabled>
                                </div>
                            <?php else : ?>
                                <div class="form-group">
                                    <label class="form-label">Status Kelulusan</label>
                                    <input type="text" class="form-control" value="Belum Lulus" disabled>
                                </div>
                            <?php endif; ?>
                            <div class="section-divider">Riwayat Mutasi (Jika Ada)</div>
                            <?php if (!empty($mutasi_jenis) && $mutasi_jenis !== '-') : ?>
                                <div class="form-group">
                                    <label class="form-label">Jenis Mutasi</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($mutasi_jenis); ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Tanggal Mutasi</label>
                                    <input type="date" class="form-control" value="<?= htmlspecialchars($mutasi_tgl); ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Asal Sekolah (Pindahan)</label>
                                    <input type="text" class="form-control" value="<?= ($mutasi_jenis == 'Mutasi Keluar') ? 'SMK Islam 1 Blitar' : htmlspecialchars($mutasi_from ?? '-'); ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Sekolah Tujuan</label>
                                    <input type="text" class="form-control" value="<?= ($mutasi_jenis == 'Mutasi Masuk') ? 'SMK Islam 1 Blitar' : htmlspecialchars($mutasi_to ?? '-'); ?>" disabled>
                                </div>
                                <div class="form-group full-width">
                                    <label class="form-label">Alasan Mutasi</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($mutasi_alasan); ?>" disabled>
                                </div>
                            <?php else : ?>
                                <div class="form-group">
                                    <label class="form-label">Riwayat Mutasi</label>
                                    <input type="text" class="form-control" value="Tidak Ada Riwayat Mutasi" disabled>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <button id="btn-simpan" type="submit" class="btn-submit">
                    <i class="bi bi-check-circle-fill"></i> Simpan Seluruh Data
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>