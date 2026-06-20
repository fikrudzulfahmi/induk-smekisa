<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>



<div id="main">
    <div class="page-heading">
        <h3>Profil Sekolah</h3>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <h4>Data Profil Sekolah</h4>
                <p>Pastikan data di bawah ini sesuai dengan data Dapodik.</p>
            </div>
            <div class="card-body">
                <form action="<?= BASEURL; ?>/profil/updateProfilSekolah" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Dasar</h6>
                            <div class="form-group mb-2">
                                <label for="nama_sekolah" class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="nama_sekolah" name="nama_sekolah" value="<?= htmlspecialchars($data['profil']->nama_sekolah ?? ''); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                    <label for="npsn" class="form-label">NPSN</label>
                                    <input type="text" class="form-control form-control-sm" id="npsn" name="npsn" value="<?= htmlspecialchars($data['profil']->npsn ?? ''); ?>">
                                </div>
                                <div class="col-md-6 form-group mb-2">
                                    <label for="nss" class="form-label">NSS</label>
                                    <input type="text" class="form-control form-control-sm" id="nss" name="nss" value="<?= htmlspecialchars($data['profil']->nss ?? ''); ?>">
                                </div>
                            </div>
                            <hr>
                            <h6>Alamat</h6>
                            <div class="form-group mb-2">
                                <label for="alamat" class="form-label">Alamat Jalan</label>
                                <textarea class="form-control form-control-sm" id="alamat" name="alamat" rows="2"><?= htmlspecialchars($data['profil']->alamat ?? ''); ?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group mb-2">
                                    <label for="kode_pos" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control form-control-sm" id="kode_pos" name="kode_pos" value="<?= htmlspecialchars($data['profil']->kode_pos ?? ''); ?>">
                                </div>
                                <div class="col-md-8 form-group mb-2">
                                    <label for="kelurahan" class="form-label">Kelurahan</label>
                                    <input type="text" class="form-control form-control-sm" id="kelurahan" name="kelurahan" value="<?= htmlspecialchars($data['profil']->kelurahan ?? ''); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <input type="text" class="form-control form-control-sm" id="kecamatan" name="kecamatan" value="<?= htmlspecialchars($data['profil']->kecamatan ?? ''); ?>">
                                </div>
                                <div class="col-md-6 form-group mb-2">
                                    <label for="kota" class="form-label">Kabupaten/Kota</label>
                                    <input type="text" class="form-control form-control-sm" id="kota" name="kota" value="<?= htmlspecialchars($data['profil']->kota ?? ''); ?>">
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label for="provinsi" class="form-label">Provinsi</label>
                                <input type="text" class="form-control form-control-sm" id="provinsi" name="provinsi" value="<?= htmlspecialchars($data['profil']->provinsi ?? ''); ?>">
                            </div>

                            <hr>
                            <h6>Token Siswa</h6>
                            <div class="form-group mb-2">
                                <label for="token" class="form-label">Token</label>
                                <input class="form-control form-control-sm" id="token" name="token" value="<?= htmlspecialchars($data['profil']->token ?? ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6>Kontak & Website</h6>
                            <div class="form-group mb-2">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control form-control-sm" id="telepon" name="telepon" value="<?= htmlspecialchars($data['profil']->telepon ?? ''); ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control form-control-sm" id="website" name="website" placeholder="https://..." value="<?= htmlspecialchars($data['profil']->website ?? ''); ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email" value="<?= htmlspecialchars($data['profil']->email ?? ''); ?>">
                            </div>
                            <hr>
                            <h6>Kepala Sekolah</h6>
                            <div class="form-group mb-2">
                                <label for="nama_kepsek" class="form-label">Nama Kepala Sekolah</label>
                                <input type="text" class="form-control form-control-sm" id="nama_kepsek" name="nama_kepsek" value="<?= htmlspecialchars($data['profil']->nama_kepsek ?? ''); ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="nip_kepsek" class="form-label">NIP Kepala Sekolah</label>
                                <input type="text" class="form-control form-control-sm" id="nip_kepsek" name="nip_kepsek" value="<?= htmlspecialchars($data['profil']->nip_kepsek ?? ''); ?>">
                            </div>
                            <hr>
                            <h6>Pengaturan Rapor</h6>
                            <div class="form-group mb-2">
                                <label for="versi_erapor" class="form-label">Versi E-Rapor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="versi_erapor" name="versi_erapor" value="<?= htmlspecialchars($data['profil']->versi_erapor ?? 'v1.0.0'); ?>" placeholder="Contoh: v1.2.0" required>
                                <small class="form-text text-muted">Akan tampil di footer cetakan rapor.</small>
                            </div>
                            <hr>
                            <h6>Logo Sekolah</h6>
                            <div class="form-group mb-2">
                                <label for="logo_sekolah" class="form-label">Upload Logo Baru</label>
                                <input class="form-control form-control-sm" type="file" id="logo_sekolah" name="logo_sekolah" accept="image/png, image/jpeg, image/gif">
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah. Max: 1MB (png/jpg/gif).</small>

                                <?php // Tampilkan logo saat ini jika ada
                                $current_logo_url = null;
                                if (!empty($data['profil']->logo_sekolah)) {
                                    $logo_relative_path = ltrim($data['profil']->logo_sekolah, './'); // Hapus ./ di awal
                                    // Asumsi logo ada di folder public/images/logo/
                                    if (file_exists($logo_relative_path)) { // Cek file relatif dari index.php
                                        $current_logo_url = BASEURL . '/' . $logo_relative_path;
                                    }
                                }
                                ?>
                                <?php if ($current_logo_url) : ?>
                                    <div class="mt-2">
                                        <p><small>Logo saat ini:</small></p>
                                        <img src="<?= $current_logo_url; ?>?t=<?= time(); // Cache busting 
                                                                                ?>" alt="Logo Saat Ini" style="max-height: 80px; border: 1px solid #ddd;">
                                    </div>
                                <?php else : ?>
                                    <p class="text-muted mt-2"><small>Belum ada logo terupload.</small></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary mt-2"><i class="bi bi-save-fill"></i> Simpan Perubahan Profil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>