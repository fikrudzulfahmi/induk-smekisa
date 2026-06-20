<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Form Mutasi Masuk Siswa</h3>
                    <p class="text-subtitle text-muted">Masukkan data siswa pindahan.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= BASEURL; ?>/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= BASEURL; ?>/mutasi">Mutasi Siswa</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Mutasi Masuk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>
        <section class="section">
            <form action="<?= BASEURL; ?>/mutasi/prosesMasuk" method="post">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Input Data Siswa Pindahan</h4>
                        <div>
                            <a href="<?= BASEURL; ?>/mutasi" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2-fill"></i> Simpan Data Mutasi Masuk</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php $old = $data['old_input'] ?? []; ?>

                        <hr>
                        <h5 class="mt-4">Informasi Pindahan <span class="text-danger">*</span></h5>
                        <div class="row mb-3">
                            <label for="tgl_diterima" class="col-sm-3 col-form-label">Tanggal Diterima <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="tgl_diterima" id="tgl_diterima" value="<?= $old['tgl_diterima'] ?? ''; ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="asal_sekolah" class="col-sm-3 col-form-label">Asal Sekolah <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="asal_sekolah" id="asal_sekolah" placeholder="Nama Sekolah Sebelumnya" value="<?= htmlspecialchars($old['asal_sekolah'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="alasan_pindah" class="col-sm-3 col-form-label">Alasan Pindah <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="alasan_pindah" id="alasan_pindah" rows="3" required><?= htmlspecialchars($old['alasan_pindah'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        <hr>

                        <h5 class="mt-3">A. Keterangan Diri Siswa</h5>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nama_siswa" value="<?= htmlspecialchars($old['nama_siswa'] ?? ''); ?>" required></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nama Panggilan</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nama_panggilan" value="<?= htmlspecialchars($old['nama_panggilan'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nomor Induk Siswa <span class="text-danger">*</span></label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="no_induk" value="<?= htmlspecialchars($old['no_induk'] ?? ''); ?>" required></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">NISN <span class="text-danger">*</span></label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nisn" value="<?= htmlspecialchars($old['nisn'] ?? ''); ?>" required></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">NIK</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nik_siswa" value="<?= htmlspecialchars($old['nik_siswa'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">NKK</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nkk" value="<?= htmlspecialchars($old['nkk'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nomor Akta</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="no_akta" value="<?= htmlspecialchars($old['no_akta'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select" name="jenis_kelamin" required>
                                    <option value="">Pilih...</option>
                                    <option value="Laki-Laki" <?= (($old['jenis_kelamin'] ?? '') == 'Laki-Laki') ? 'selected' : ''; ?>>Laki-Laki</option>
                                    <option value="Perempuan" <?= (($old['jenis_kelamin'] ?? '') == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Tempat & Tgl. Lahir <span class="text-danger">*</span></label>
                            <div class="col-sm-5"><input type="text" class="form-control" name="tmpt_lhr" value="<?= htmlspecialchars($old['tmpt_lhr'] ?? ''); ?>" placeholder="Tempat Lahir" required></div>
                            <div class="col-sm-4"><input type="date" class="form-control" name="tgl_lhr" value="<?= $old['tgl_lhr'] ?? ''; ?>" required></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Agama</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="agama" value="<?= htmlspecialchars($old['agama'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Kewarganegaraan</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="kewarganegaraan" value="<?= htmlspecialchars($old['kewarganegaraan'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Anak Ke-</label>
                            <div class="col-sm-9"><input type="number" class="form-control" name="anak_ke" value="<?= htmlspecialchars($old['anak_ke'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Jumlah Saudara Kandung</label>
                            <div class="col-sm-9"><input type="number" class="form-control" name="jml_sdr_kandung" value="<?= htmlspecialchars($old['jml_sdr_kandung'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Jumlah Saudara Tiri</label>
                            <div class="col-sm-9"><input type="number" class="form-control" name="jml_sdr_tiri" value="<?= htmlspecialchars($old['jml_sdr_tiri'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Jumlah Saudara Angkat</label>
                            <div class="col-sm-9"><input type="number" class="form-control" name="jml_sdr_angkat" value="<?= htmlspecialchars($old['jml_sdr_angkat'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Status Anak</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="yatim_piatu" value="<?= htmlspecialchars($old['yatim_piatu'] ?? ''); ?>" placeholder="Yatim/Piatu/Yatim Piatu/Lengkap"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Bahasa Sehari-hari</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="bahasa" value="<?= htmlspecialchars($old['bahasa'] ?? ''); ?>"></div>
                        </div>

                        <h5 class="mt-4">B. Keterangan Tempat Tinggal</h5>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-9"><input class="form-control" name="alamat" value="<?= htmlspecialchars($old['alamat'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Detail Alamat</label>
                            <div class="col-sm-1"><label class="col-form-label">Dusun</label></div>
                            <div class="col-sm-2"><input type="text" class="form-control" placeholder="Dusun" name="dusun" value="<?= htmlspecialchars($old['dusun'] ?? ''); ?>"></div>
                            <div class="col-sm-1"><label class="col-form-label">RT</label></div>
                            <div class="col-sm-2"><input type="text" class="form-control" placeholder="RT" name="rt" value="<?= htmlspecialchars($old['rt'] ?? ''); ?>"></div>
                            <div class="col-sm-1"><label class="col-form-label">RW</label></div>
                            <div class="col-sm-2"><input type="text" class="form-control" placeholder="RW" name="rw" value="<?= htmlspecialchars($old['rw'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-1"><label class="col-form-label">Desa/Kel.</label></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Desa/Kelurahan" name="desa" value="<?= htmlspecialchars($old['desa'] ?? ''); ?>"></div>
                            <div class="col-sm-1"><label class="col-form-label">Kecamatan</label></div>
                            <div class="col-sm-3"><input type="text" class="form-control" placeholder="Kecamatan" name="kec" value="<?= htmlspecialchars($old['kec'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-1"><label class="col-form-label">Kab./Kota</label></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Kab./Kota" name="kab" value=" <?= htmlspecialchars($old['kab'] ?? ''); ?>"></div>
                            <div class="col-sm-1"><label class="col-form-label">Kode Pos</label></div>
                            <div class="col-sm-3"><input type="text" class="form-control" placeholder="Kode Pos" name="kd_pos" value="<?= htmlspecialchars($old['kd_pos'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-1"><label class="col-form-label">Provinsi</label></div>
                            <div class="col-sm-8"><input type="text" class="form-control" placeholder="Provinsi" name="provinsi" value="<?= htmlspecialchars($old['provinsi'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Kontak</label>
                            <div class="col-sm-1"><label class="col-form-label">Telp.</label></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Telepon" name="no_tlp" value="<?= htmlspecialchars($old['no_tlp'] ?? ''); ?>"></div>
                            <div class="col-sm-1"><label class="col-form-label">HP</label></div>
                            <div class="col-sm-3"><input type="text" class="form-control" placeholder="HP" name="no_hp" value="<?= htmlspecialchars($old['no_hp'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Tinggal Dengan</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="tinggal_bersama" value="<?= htmlspecialchars($old['tinggal_bersama'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Jarak Rumah ke Sekolah</label>
                            <div class="col-sm-4"><input type="number" step="0.1" class="form-control" placeholder="Jarak (km)" name="jarak_rumah" value="<?= htmlspecialchars($old['jarak_rumah'] ?? ''); ?>"></div>
                            <div class="col-sm-2"><label class="col-form-label">Waktu Tempuh</label></div>
                            <div class="col-sm-3"><input type="number" class="form-control" placeholder="Waktu (menit)" name="wkt_tempuh" value="<?= htmlspecialchars($old['wkt_tempuh'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Transportasi ke Sekolah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="transportasi" value="<?= htmlspecialchars($old['transportasi'] ?? ''); ?>"></div>
                        </div>

                        <h5 class="mt-4">C. Keterangan Kesehatan</h5>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Golongan Darah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="gol_darah" value="<?= htmlspecialchars($old['gol_darah'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Penyakit yang Pernah Diderita</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="penyakit" value="<?= htmlspecialchars($old['penyakit'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Kelainan Jasmani</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="kelainan_jasmani" value="<?= htmlspecialchars($old['kelainan_jasmani'] ?? ''); ?>"></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Tinggi Badan</label>
                            <div class="col-sm-3"><input type="number" class="form-control" placeholder="Tinggi (cm)" name="tb" value="<?= htmlspecialchars($old['tb'] ?? ''); ?>"></div>
                            <div class="col-sm-1"><label class="col-form-label">cm</label></div>
                            <div class="col-sm-1"><label class="col-form-label">Berat Badan</label>
                                <div class="col-sm-2"><input type="number" class="form-control" placeholder="Berat (kg)" name="bb" value="<?= htmlspecialchars($old['bb'] ?? ''); ?>"></div>
                                <div class="col-sm-1"><label class="col-form-label">kg</label></div>
                            </div>

                            <h5 class="mt-4">D. Keterangan Pendidikan Sebelumnya</h5>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Pendidikan Dasar</label>
                                <div class="col-sm-1"><label class="col-form-label">Asal SD/MI</label></div>
                                <div class="col-sm-4"><input type="text" class="form-control" placeholder="SD/MI" name="asal_sd" value="<?= htmlspecialchars($old['asal_sd'] ?? ''); ?>"></div>
                                <div class="col-sm-1"><label class="col-form-label">NPSN SD/MI</label></div>
                                <div class="col-sm-3"><input type="text" class="form-control" placeholder="NPSN SD/MI" name="npsn_sd" value="<?= htmlspecialchars($old['npsn_sd'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Pendidikan Menengah Pertama</label>
                                <div class="col-sm-2"><label class="col-form-label">Lulusan Dari</label></div>
                                <div class="col-sm-7"><input type="text" class="form-control" name="pend_sebelumnya" value="<?= htmlspecialchars($old['pend_sebelumnya'] ?? ''); ?>" placeholder="SMP/MTs"></div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-2"><label class="col-form-label">Asal SMP/MTs</label></div>
                                <div class="col-sm-7"><input type="text" class="form-control" name="asal_smp" value="<?= htmlspecialchars($old['asal_smp'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-2"><label class="col-form-label">Alamat SMP/MTs</label></div>
                                <div class="col-sm-7"><input type="text" class="form-control" name="alamat_smp" value="<?= htmlspecialchars($old['alamat_smp'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-2"><label class="col-form-label">NPSN SMP/MTs</label></div>
                                <div class="col-sm-7"><input type="text" class="form-control" name="npsn_smp" value="<?= htmlspecialchars($old['npsn_smp'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-2"><label class="col-form-label">Nomor Seri Ijazah</label></div>
                                <div class="col-sm-7"><input type="text" class="form-control" name="seri_ijazah_smp" value="<?= htmlspecialchars($old['seri_ijazah_smp'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-2"><label class="col-form-label">Tgl Ijazah</label></div>
                                <div class="col-sm-3"><input type="date" class="form-control" name="tgl_ijazah_smp" value="<?= $old['tgl_ijazah_smp'] ?? ''; ?>"></div>
                                <div class="col-sm-2"><label class="col-form-label">Tahun Ijazah</label></div>
                                <div class="col-sm-2"><input type="number" class="form-control" placeholder="Tahun" name="th_ijazah_smp" value="<?= htmlspecialchars($old['th_ijazah_smp'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-2"><label class="col-form-label">Lama Belajar</label></div>
                                <div class="col-sm-1"><input type="number" class="form-control" name="lama_belajar_smp" value="<?= htmlspecialchars($old['lama_belajar_smp'] ?? ''); ?>"></div>
                                <div class="col-sm-6"><label class="col-form-label">Tahun</label></div>
                            </div>


                            <h5 class="mt-4">E. Keterangan Pendidikan (Sekolah Ini) <span class="text-danger">*</span></h5>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Diterima di Tingkat <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="tingkat" id="tingkat" required>
                                        <option value="">Pilih Tingkat</option>
                                        <?php $oldTingkat = $old['tingkat'] ?? ''; ?>
                                        <option value="X" <?= ($oldTingkat == 'X') ? 'selected' : ''; ?>>X (Sepuluh)</option>
                                        <option value="XI" <?= ($oldTingkat == 'XI') ? 'selected' : ''; ?>>XI (Sebelas)</option>
                                        <option value="XII" <?= ($oldTingkat == 'XII') ? 'selected' : ''; ?>>XII (Dua Belas)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Kompetensi Keahlian <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="komp_keahlian" required>
                                        <option value="">Pilih Jurusan...</option>
                                        <?php foreach ($data['jurusan'] as $jurusan) : ?>
                                            <option value="<?= $jurusan->id_jurusan; ?>" <?= ($jurusan->id_jurusan == ($old['komp_keahlian'] ?? null)) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($jurusan->jurusan); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Rombongan Belajar <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="rombel" required>
                                        <option value="">Pilih Rombel...</option>
                                        <?php foreach ($data['rombel'] as $rombel_item) : ?>
                                            <option value="<?= $rombel_item->id_rombel; ?>" <?= ($rombel_item->id_rombel == ($old['rombel'] ?? null)) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($rombel_item->nama_rombel); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <h5 class="mt-3">F. Keterangan Ayah Kandung</h5>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Nama Ayah</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="nama_ayah" value="<?= htmlspecialchars($old['nama_ayah'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">NIK Ayah</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="nik_ayah" value="<?= htmlspecialchars($old['nik_ayah'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Tempat & Tgl. Lahir Ayah</label>
                                <div class="col-sm-5"><input type="text" class="form-control" name="tmpt_lhr_ayah" value="<?= htmlspecialchars($old['tmpt_lhr_ayah'] ?? ''); ?>"></div>
                                <div class="col-sm-4"><input type="date" class="form-control" name="tgl_lhr_ayah" value="<?= $old['tgl_lhr_ayah'] ?? ''; ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Agama Ayah</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="agama_ayah" value="<?= htmlspecialchars($old['agama_ayah'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Kewarganegaraan Ayah</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="kewarganegaraan_ayah" value="<?= htmlspecialchars($old['kewarganegaraan_ayah'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Pendidikan Ayah</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="pend_ayah" value="<?= htmlspecialchars($old['pend_ayah'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Pekerjaan Ayah</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="pekerjaan_ayah" value="<?= htmlspecialchars($old['pekerjaan_ayah'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Penghasilan Ayah</label>
                                <div class="col-sm-9"><input type="text" class="form-control input-gaji" name="penghasilan_ayah" value="<?= htmlspecialchars($old['penghasilan_ayah'] ?? '0'); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Alamat Ayah</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="alamat_ayah" value="<?= htmlspecialchars($old['alamat_ayah'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">No. HP Ayah</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="hp_ayah" value="<?= htmlspecialchars($old['hp_ayah'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Hidup/Mati</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="hidup_mati_ayah" value="<?= htmlspecialchars($old['hidup_mati_ayah'] ?? ''); ?>" placeholder="Masih Hidup / Meninggal Dunia"></div>
                            </div>

                            <h5 class="mt-4">G. Keterangan Ibu Kandung</h5>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Nama Ibu</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="nama_ibu" value="<?= htmlspecialchars($old['nama_ibu'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">NIK Ibu</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="nik_ibu" value="<?= htmlspecialchars($old['nik_ibu'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Tempat & Tgl. Lahir Ibu</label>
                                <div class="col-sm-5"><input type="text" class="form-control" name="tmpt_lhr_ibu" value="<?= htmlspecialchars($old['tmpt_lhr_ibu'] ?? ''); ?>"></div>
                                <div class="col-sm-4"><input type="date" class="form-control" name="tgl_lhr_ibu" value="<?= $old['tgl_lhr_ibu'] ?? ''; ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Agama Ibu</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="agama_ibu" value="<?= htmlspecialchars($old['agama_ibu'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Kewarganegaraan Ibu</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="kewarganegaraan_ibu" value="<?= htmlspecialchars($old['kewarganegaraan_ibu'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Pendidikan Ibu</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="pend_ibu" value="<?= htmlspecialchars($old['pend_ibu'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Pekerjaan Ibu</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="pekerjaan_ibu" value="<?= htmlspecialchars($old['pekerjaan_ibu'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Penghasilan Ibu</label>
                                <div class="col-sm-9"><input type="text" class="form-control input-gaji" name="penghasilan_ibu" value="<?= htmlspecialchars($old['penghasilan_ibu'] ?? '0'); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Alamat Ibu</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="alamat_ibu" value="<?= htmlspecialchars($old['alamat_ibu'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">No. HP Ibu</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="hp_ibu" value="<?= htmlspecialchars($old['hp_ibu'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Hidup/Mati</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="hidup_mati_ibu" value="<?= htmlspecialchars($old['hidup_mati_ibu'] ?? ''); ?>" placeholder="Masih Hidup / Meninggal Dunia"></div>
                            </div>

                            <h5 class="mt-4">H. Keterangan Wali</h5>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Nama Wali</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="nama_wali" value="<?= htmlspecialchars($old['nama_wali'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">NIK Wali</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="nik_wali" value="<?= htmlspecialchars($old['nik_wali'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Tempat & Tgl. Lahir Wali</label>
                                <div class="col-sm-5"><input type="text" class="form-control" name="tmpt_lhr_wali" value="<?= htmlspecialchars($old['tmpt_lhr_wali'] ?? ''); ?>"></div>
                                <div class="col-sm-4"><input type="date" class="form-control" name="tgl_lhr_wali" value="<?= $old['tgl_lhr_wali'] ?? ''; ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Agama Wali</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="agama_wali" value="<?= htmlspecialchars($old['agama_wali'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Kewarganegaraan Wali</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="kewarganegaraan_wali" value="<?= htmlspecialchars($old['kewarganegaraan_wali'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Pendidikan Wali</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="pend_wali" value="<?= htmlspecialchars($old['pend_wali'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Pekerjaan Wali</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="pekerjaan_wali" value="<?= htmlspecialchars($old['pekerjaan_wali'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Penghasilan Wali</label>
                                <div class="col-sm-9"><input type="text" class="form-control input-gaji" name="penghasilan_wali" value="<?= htmlspecialchars($old['penghasilan_wali'] ?? '0'); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Alamat Wali</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="alamat_wali" value="<?= htmlspecialchars($old['alamat_wali'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">No. HP Wali</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="hp_wali" value="<?= htmlspecialchars($old['hp_wali'] ?? ''); ?>"></div>
                            </div>


                            <h5 class="mt-4">I. Kegemaran / Hobi</h5>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Kesenian</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="kesenian" value="<?= htmlspecialchars($old['kesenian'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Olah Raga</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="olahraga" value="<?= htmlspecialchars($old['olahraga'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Organisasi</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="organisasi" value="<?= htmlspecialchars($old['organisasi'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Cita-cita</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="cita_cita" value="<?= htmlspecialchars($old['cita_cita'] ?? ''); ?>"></div>
                            </div>
                            <div class="row mb-3"><label class="col-sm-3 col-form-label">Lain-lain</label>
                                <div class="col-sm-9"><input type="text" class="form-control" name="lain_lain" value="<?= htmlspecialchars($old['lain_lain'] ?? ''); ?>"></div>
                            </div>


                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <a href="<?= BASEURL; ?>/mutasi" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2-fill"></i> Simpan Data Mutasi Masuk</button>
                        </div>
                    </div>
            </form>
        </section>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>