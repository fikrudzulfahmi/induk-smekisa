<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>
<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tambah Data Induk Siswa Baru</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>
        <section class="section">
            <form action="<?= BASEURL; ?>/siswa/prosesTambah" method="post">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Form Tambah Siswa</h4>
                        <div>
                            <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy2-fill"></i> Simpan Data</button>
                        </div>
                    </div>
                    <div class="card-body">

                        <h5 class="mt-3">A. Keterangan Diri Siswa</h5>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nama_siswa" required></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Nama Panggilan</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nama_panggilan"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Nomor Induk Siswa</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="no_induk" required></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">NISN</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nisn"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">NIK</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nik"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">NKK</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nkk"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Nomor Akta</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="no_akta"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="jenis_kelamin" required>
                                    <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Tempat & Tgl. Lahir</label>
                            <div class="col-sm-5"><input type="text" class="form-control" name="tmpt_lhr" placeholder="Tempat Lahir"></div>
                            <div class="col-sm-4"><input type="date" class="form-control" name="tgl_lhr"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Agama</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="agama"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Kewarganegaraan</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="kewarganegaraan"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Anak Ke-</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="anak_ke"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Jumlah Saudara Kandung</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="jml_sdr_kandung"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Jumlah Saudara Tiri</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="jml_sdr_tiri"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Jumlah Saudara Angkat</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="jml_sdr_angkat"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Yatim/Piatu</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="yatim_piatu"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Bahasa Sehari-hari</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="bahasa"></div>
                        </div>

                        <h5 class="mt-4">B. Keterangan Tempat Tinggal</h5>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-9"><input class="form-control" name="alamat"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Detail Alamat</label>
                            <div class="col-sm-1"><label class="col-form-label">Dusun</label></div>
                            <div class="col-sm-2"><input type="text" class="form-control" placeholder="Dusun" name="dusun"></div>
                            <div class="col-sm-1"><label class="col-form-label">RT</label></div>
                            <div class="col-sm-2"><input type="text" class="form-control" placeholder="RT" name="rt"></div>
                            <div class="col-sm-1"><label class="col-form-label">RW</label></div>
                            <div class="col-sm-2"><input type="text" class="form-control" placeholder="RW" name="rw"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-1"><label class="col-form-label">Desa/Kel.</label></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Desa/Kelurahan" name="desa"></div>
                            <div class="col-sm-1"><label class="col-form-label">Kecamatan</label></div>
                            <div class="col-sm-3"><input type="text" class="form-control" placeholder="Kecamatan" name="kecamatan"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-1"><label class="col-form-label">Kab./Kota</label></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Kab./Kota" name="kab"></div>
                            <div class="col-sm-1"><label class="col-form-label">Kode Pos</label></div>
                            <div class="col-sm-3"><input type="text" class="form-control" placeholder="Kode Pos" name="kd_pos"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-1"><label class="col-form-label">Provinsi</label></div>
                            <div class="col-sm-8"><input type="text" class="form-control" placeholder="Provinsi" name="provinsi"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Kontak</label>
                            <div class="col-sm-1"><label class="col-form-label">Telp.</label></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Telepon" name="no_tlp"></div>
                            <div class="col-sm-1"><label class="col-form-label">HP</label></div>
                            <div class="col-sm-3"><input type="text" class="form-control" placeholder="HP" name="no_hp"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Tinggal Dengan</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="tinggal_bersama"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Jarak rumah ke sekolah</label>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="Jarak (km)" name="jarak_rumah"></div>
                            <div class="col-sm-2"><label class="col-form-label">Waktu tempuh</label></div>
                            <div class="col-sm-3"><input type="text" class="form-control" placeholder="Waktu tempuh (menit)" name="wkt_tempuh"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Transportasi ke sekolah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="transportasi"></div>
                        </div>

                        <h5 class="mt-4">C. Keterangan Kesehatan</h5>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Golongan Darah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="gol_darah"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Penyakit yang pernah diderita</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="penyakit"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Kelainan Jasmani</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="kelainan_jasmani"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Tinggi badan</label>
                            <div class="col-sm-3"><input type="text" class="form-control" placeholder="Tinggi (cm)" name="tb"></div>
                            <div class="col-sm-1"><label class="col-form-label">cm</label></div>
                            <div class="col-sm-1"><label class="col-form-label">Berat badan</label></div>
                            <div class="col-sm-2"><input type="text" class="form-control" placeholder="Berat (kg)" name="bb"></div>
                            <div class="col-sm-1"><label class="col-form-label">kg</label></div>
                        </div>

                        <h5 class="mt-4">D. Keterangan Pendidikan</h5>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Pendidikan Dasar</label>
                            <div class="col-sm-1"><label class="col-form-label">Asal SD/MI</label></div>
                            <div class="col-sm-4"><input type="text" class="form-control" placeholder="SD/MI" name="asal_sd"></div>
                            <div class="col-sm-1"><label class="col-form-label">NPSN SD/MI</label></div>
                            <div class="col-sm-3"><input type="text" class="form-control" placeholder="NPSN SD/MI" name="npsn_sd"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Pendidikan Menengah Pertama</label>
                            <div class="col-sm-2"><label class="col-form-label">Pendidikan Sebelumnya</label></div>
                            <div class="col-sm-7"><input type="text" class="form-control" name="pend_sebelumnya"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-2"><label class="col-form-label">Asal SMP</label></div>
                            <div class="col-sm-7"><input type="text" class="form-control" name="asal_smp"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-2"><label class="col-form-label">Alamat SMP</label></div>
                            <div class="col-sm-7"><input type="text" class="form-control" name="alamat_smp"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-2"><label class="col-form-label">NPSN SMP</label></div>
                            <div class="col-sm-7"><input type="text" class="form-control" name="npsn_smp"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-2"><label class="col-form-label">Seri Ijazah SMP</label></div>
                            <div class="col-sm-7"><input type="text" class="form-control" name="seri_ijazah_smp"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-2"><label class="col-form-label">Tgl Ijazah SMP</label></div>
                            <div class="col-sm-3"><input type="date" class="form-control" name="tgl_ijazah_smp"></div>
                            <div class="col-sm-2"><label class="col-form-label">Tahun Ijazah SMP</label></div>
                            <div class="col-sm-2"><input type="text" class="form-control" placeholder="Tahun" name="th_ijazah_smp"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-2"><label class="col-form-label">Lama Belajar SMP</label></div>
                            <div class="col-sm-1"><input type="text" class="form-control" name="lama_belajar_smp"></div>
                            <div class="col-sm-6"><label class="col-form-label">Tahun</label></div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Tingkat Diterima</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="tingkat" id="tingkat">
                                    <option value="" selected disabled>Pilih Tingkat</option>
                                    <?php
                                    $tingkat_options = ["X ( Sepuluh )", "XI ( Sebelas )", "XII ( Dua Belas )"];
                                    foreach ($tingkat_options as $option) : ?>
                                        <option value="<?= htmlspecialchars($option); ?>">
                                            <?= htmlspecialchars($option); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Kompetensi Keahlian</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="komp_keahlian" required>
                                    <option value="" selected disabled>Pilih Jurusan / Keahlian</option>
                                    <?php foreach ($data['jurusan'] as $jurusan) : ?>
                                        <option value="<?= $jurusan->id_jurusan; ?>">
                                            <?= htmlspecialchars($jurusan->jurusan); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Diterima Tanggal</label>
                            <div class="col-sm-9"><input type="date" class="form-control" name="diterima_tgl"></div>
                        </div>

                        <h5 class="mt-3">F. Keterangan Ayah Kandung</h5>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Nama Ayah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nama_ayah"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">NIK Ayah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nik_ayah"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Tempat & Tgl. Lahir Ayah</label>
                            <div class="col-sm-5"><input type="text" class="form-control" name="tmpt_lhr_ayah" placeholder="Tempat Lahir"></div>
                            <div class="col-sm-4"><input type="date" class="form-control" name="tgl_lhr_ayah"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Agama Ayah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="agama_ayah"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Kewarganegaraan Ayah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="kewarganegaraan_ayah"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Pendidikan Ayah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="pend_ayah"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Pekerjaan Ayah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="pekerjaan_ayah"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Penghasilan Ayah</label>
                            <div class="col-sm-9"><input type="text" class="form-control input-gaji" name="penghasilan_ayah" placeholder="0"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Alamat Ayah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="alamat_ayah"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">No. HP Ayah</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="hp_ayah"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Hidup/Mati</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="hidup_mati_ayah"></div>
                        </div>

                        <h5 class="mt-4">G. Keterangan Ibu Kandung</h5>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Nama Ibu</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nama_ibu"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">NIK Ibu</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nik_ibu"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Tempat & Tgl. Lahir Ibu</label>
                            <div class="col-sm-5"><input type="text" class="form-control" name="tmpt_lhr_ibu" placeholder="Tempat Lahir"></div>
                            <div class="col-sm-4"><input type="date" class="form-control" name="tgl_lhr_ibu"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Agama Ibu</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="agama_ibu"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Kewarganegaraan Ibu</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="kewarganegaraan_ibu"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Pendidikan Ibu</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="pend_ibu"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Pekerjaan Ibu</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="pekerjaan_ibu"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Penghasilan Ibu</label>
                            <div class="col-sm-9"><input type="text" class="form-control input-gaji" name="penghasilan_ibu" placeholder="0"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Alamat Ibu</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="alamat_ibu"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">No. HP Ibu</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="hp_ibu"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Hidup/Mati</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="hidup_mati_ibu"></div>
                        </div>

                        <h5 class="mt-4">H. Keterangan Wali</h5>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Nama Wali</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nama_wali"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">NIK Wali</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="nik_wali"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Tempat & Tgl. Lahir Wali</label>
                            <div class="col-sm-5"><input type="text" class="form-control" name="tmpt_lhr_wali" placeholder="Tempat Lahir"></div>
                            <div class="col-sm-4"><input type="date" class="form-control" name="tgl_lhr_wali"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Agama Wali</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="agama_wali"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Kewarganegaraan Wali</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="kewarganegaraan_wali"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Pendidikan Wali</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="pend_wali"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Pekerjaan Wali</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="pekerjaan_wali"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Penghasilan Wali</label>
                            <div class="col-sm-9"><input type="text" class="form-control input-gaji" name="penghasilan_wali" placeholder="0"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Alamat Wali</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="alamat_wali"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">No. HP Wali</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="hp_wali"></div>
                        </div>

                        <h5 class="mt-4">I. Kegemaran / Hobi</h5>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Kesenian</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="kesenian"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Olah Raga</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="olahraga"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Organisasi</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="organisasi"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Cita-cita</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="cita_cita"></div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Lain-lain</label>
                            <div class="col-sm-9"><input type="text" class="form-control" name="lain_lain"></div>
                        </div>

                        <h5 class="mt-4">J. Status</h5>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Status Siswa</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="id_status" required>
                                    <option value="" selected disabled>Pilih Status</option>
                                    <?php foreach ($data['status_list'] as $status) : ?>
                                        <option value="<?= $status->id_status; ?>">
                                            <?= htmlspecialchars($status->status); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3"><label class="col-sm-3 col-form-label">Rombel</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="rombel" required>
                                    <option value="" selected disabled>Pilih Rombel</option>
                                    <?php foreach ($data['rombel'] as $rombel) : ?>
                                        <option value="<?= $rombel->id_rombel; ?>">
                                            <?= htmlspecialchars($rombel->nama_rombel); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
<?php require_once '../app/views/templates/footer.php'; ?>