<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>



<div id="main">
    <div class="page-heading">
        <h3>Profil Sekolah</h3>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>
        <div class="card">
            <div class="card-header">
                <h4>Data Profil Sekolah</h4>
                <a href="<?= BASEURL; ?>/profil/edit" class="btn btn-sm btn-primary float-end mt-n4"><i class="bi bi-pencil-square"></i> Edit Profil</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">Nama Sekolah</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->nama_sekolah ?? '-'); ?></dd>

                            <dt class="col-sm-4">NPSN</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->npsn ?? '-'); ?></dd>

                            <dt class="col-sm-4">NSS</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->nss ?? '-'); ?></dd>
                        </dl>
                        <hr>
                        <h6>Alamat</h6>
                        <dl class="row">
                            <dt class="col-sm-4">Jalan</dt>
                            <dd class="col-sm-8">: <?= nl2br(htmlspecialchars($data['profil']->alamat ?? '-')); ?></dd>

                            <dt class="col-sm-4">Kode Pos</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->kode_pos ?? '-'); ?></dd>

                            <dt class="col-sm-4">Kelurahan</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->kelurahan ?? '-'); ?></dd>

                            <dt class="col-sm-4">Kecamatan</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->kecamatan ?? '-'); ?></dd>

                            <dt class="col-sm-4">Kabupaten/Kota</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->kota ?? '-'); ?></dd>

                            <dt class="col-sm-4">Provinsi</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->provinsi ?? '-'); ?></dd>
                        </dl>
                        <hr>
                        <h6>Token Siswa</h6>
                        <dl class="row">
                            <dt class="col-sm-4">Token</dt>
                            <dd class="col-sm-8">: <strong><?= nl2br(htmlspecialchars($data['profil']->token ?? '-')); ?></strong></dd>
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <h6>Kontak & Website</h6>
                        <dl class="row">
                            <dt class="col-sm-4">Telepon</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->telepon ?? '-'); ?></dd>

                            <dt class="col-sm-4">Website</dt>
                            <dd class="col-sm-8">: <?= $data['profil']->website ? '<a href="' . htmlspecialchars($data['profil']->website) . '" target="_blank">' . htmlspecialchars($data['profil']->website) . '</a>' : '-'; ?></dd>

                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">: <?= $data['profil']->email ? '<a href="mailto:' . htmlspecialchars($data['profil']->email) . '">' . htmlspecialchars($data['profil']->email) . '</a>' : '-'; ?></dd>
                        </dl>
                        <hr>
                        <h6>Kepala Sekolah</h6>
                        <dl class="row">
                            <dt class="col-sm-4">Nama</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->nama_kepsek ?? '-'); ?></dd>

                            <dt class="col-sm-4">NIP</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->nip_kepsek ?? '-'); ?></dd>
                        </dl>
                        <hr>
                        <h6>Pengaturan Rapor</h6>
                        <dl class="row">
                            <dt class="col-sm-4">Versi E-Rapor</dt>
                            <dd class="col-sm-8">: <?= htmlspecialchars($data['profil']->versi_erapor ?? '-'); ?></dd>
                        </dl>
                        <hr>
                        <h6>Logo Sekolah</h6>
                        <?php
                        $current_logo_url = null;
                        if (!empty($data['profil']->logo_sekolah)) {
                            // 1. Ambil nama file dari database
                            $logo_filename = $data['profil']->logo_sekolah;

                            // 2. Buat path relatif terhadap folder public (untuk URL)
                            $logo_web_path = 'assets/images/' . $logo_filename;

                            // 3. Buat path filesystem (relatif dari index.php) untuk cek file
                            //    Asumsi index.php ada di public/, dan assets/ ada di dalamnya
                            $logo_server_path = $logo_web_path;

                            // 4. Cek apakah file benar-benar ada di server
                            if (file_exists($logo_server_path)) {
                                // 5. Jika ada, buat URL lengkapnya
                                $current_logo_url = BASEURL . '/' . $logo_web_path;
                            }
                        }
                        ?>
                        <?php if ($current_logo_url) : ?>
                            <img src="<?= $current_logo_url; ?>?t=<?= time(); ?>" alt="Logo Sekolah" style="max-height: 80px;">
                        <?php else : ?>
                            <p>Logo belum ada.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<?php require_once '../app/views/templates/footer.php'; ?>