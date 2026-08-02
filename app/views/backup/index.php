<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Backup & Restore</h3>
                    <p class="text-subtitle text-muted">Kelola cadangan dan pemulihan database aplikasi.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <section class="section">
            <div class="row">
                <!-- Card Backup -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Backup Database</h4>
                        </div>
                        <div class="card-body">
                            <p>Fitur ini memungkinkan Anda untuk mencadangkan (backup) seluruh isi database dan mengunduhnya secara langsung ke perangkat ini.</p>
                            
                            <?php if (!empty($data['last_backup'])): ?>
                                <div class="alert alert-light-info color-info">
                                    <i class="bi bi-info-circle"></i> <strong>Status Backup Otomatis Terakhir (Cronjob):</strong><br>
                                    <strong>Waktu:</strong> <?= $data['last_backup']['created_at'] ?><br>
                                    <strong>Status:</strong> <span class="badge bg-<?= $data['last_backup']['status'] == 'sukses' ? 'success' : 'danger' ?>"><?= strtoupper($data['last_backup']['status']) ?></span><br>
                                    <strong>Keterangan:</strong> <?= $data['last_backup']['keterangan'] ?>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex justify-content-start mt-4 gap-2">
                                <a href="<?= BASEURL; ?>/backup/download" class="btn btn-primary" onclick="return confirm('Mulai mengunduh file backup database (.sql) sekarang? Proses ini akan memakan waktu sejenak.')">
                                    <i class="bi bi-download"></i> Download Backup (SQL)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card Restore -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Restore Database</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-danger">
                                <strong>PERINGATAN KERAS!</strong><br>
                                Melakukan <i>Restore</i> akan menimpa <strong>seluruh</strong> data yang ada saat ini dengan data dari file backup yang Anda unggah. Pastikan Anda mengunggah file yang benar.
                            </p>
                            
                            <form action="<?= BASEURL; ?>/backup/restore" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Apakah Anda YAKIN ingin menimpa database ini? Tindakan ini tidak bisa dibatalkan!');">
                                <div class="form-group mb-3">
                                    <label for="backup_file" class="form-label">Pilih file Backup (.sql)</label>
                                    <input class="form-control" type="file" id="backup_file" name="backup_file" accept=".sql" required>
                                </div>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-arrow-clockwise"></i> Eksekusi Restore
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>
