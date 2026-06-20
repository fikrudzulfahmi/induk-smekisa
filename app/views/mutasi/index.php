<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Mutasi Siswa</h3>
                    <p class="text-subtitle text-muted">Pilih jenis mutasi yang ingin dikelola.</p>
                </div>

            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="display-4 text-success mb-3">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <h4 class="card-title">Mutasi Masuk</h4>
                            <p class="card-text">Mendaftarkan siswa pindahan dari sekolah lain.</p>
                            <?php if (Auth::checkRole('admin')) : ?>
                                <a href="<?= BASEURL; ?>/mutasi/masuk" class="btn btn-success">
                                    Buka Form Mutasi Masuk
                                </a>
                            <?php endif; ?>
                            <a href="<?= BASEURL; ?>/mutasi/daftarMasuk" class="btn btn-outline-success"> Lihat Daftar Mutasi Masuk
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="display-4 text-danger mb-3">
                                <i class="bi bi-person-dash-fill"></i>
                            </div>
                            <h4 class="card-title">Mutasi Keluar & Mengundurkan Diri</h4>
                            <p class="card-text">Mencatat siswa yang keluar atau mengundurkan diri.</p>
                            <?php if (Auth::checkRole('admin')) : ?>
                                <a href="<?= BASEURL; ?>/mutasi/keluar" class="btn btn-danger"> Proses Mutasi Keluar
                                </a>
                            <?php endif; ?>
                            <a href="<?= BASEURL; ?>/mutasi/daftarKeluar" class="btn btn-outline-danger"> Lihat Daftar Mutasi Keluar
                            </a>
                            <a href="<?= BASEURL; ?>/mutasi/daftarMengundurkanDiri" class="btn btn-outline-warning"> Lihat Daftar Mengundurkan Diri
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>