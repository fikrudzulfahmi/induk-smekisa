<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Mutasi Keluar</h3>
                    <p class="text-subtitle text-muted">Perbarui data mutasi keluar siswa.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= BASEURL; ?>/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= BASEURL; ?>/mutasi">Mutasi</a></li>
                            <li class="breadcrumb-item"><a href="<?= BASEURL; ?>/mutasi/daftarKeluar">Daftar Keluar</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <?php Flasher::flash(); ?>
        <section class="section">
            <div class="card col-md-8">
                <div class="card-header">
                    <h4 class="card-title">Form Edit Mutasi Keluar</h4>
                </div>
                <form action="<?= BASEURL; ?>/mutasi/prosesUpdateKeluar" method="post">
                    <div class="card-body">
                        <!-- ID Mutasi Keluar (Hidden) -->
                        <input type="hidden" name="id_mutasi_keluar" value="<?= $data['log']->id_mutasi_keluar; ?>">

                        <!-- Informasi Siswa (Readonly) -->
                        <div class="form-group mb-3">
                            <label class="form-label">Siswa</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($data['log']->nama_siswa) . ' (' . htmlspecialchars($data['log']->no_induk) . ')'; ?>" readonly>
                        </div>

                        <hr>

                        <div class="form-group mb-3">
                            <label for="tgl_keluar" class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tgl_keluar" id="tgl_keluar" value="<?= $data['log']->tgl_keluar; ?>" required>
                        </div>

                        <div class="form-group mb-3" id="fieldSekolahTujuan">
                            <label for="sekolah_tujuan" class="form-label">Sekolah Tujuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sekolah_tujuan" id="sekolah_tujuan" value="<?= htmlspecialchars($data['log']->sekolah_tujuan); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="alasan_keluar" class="form-label">Alasan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="alasan_keluar" id="alasan_keluar" rows="3" required><?= htmlspecialchars($data['log']->alasan_keluar); ?></textarea>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="<?= BASEURL; ?>/mutasi/daftarKeluar" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
