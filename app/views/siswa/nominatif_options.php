<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <h3>Export Daftar Nominatif Siswa</h3>
        <p class="text-subtitle text-muted">Pilih Konsentrasi Keahlian dan Tingkat.</p>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="card col-md-6">
                <div class="card-header">
                    <h4 class="card-title">Pilih Kriteria</h4>
                </div>
                <div class="card-body">
                    <form action="<?= BASEURL; ?>/siswa/exportNominatif" method="post" target="_blank">
                        <div class="form-group mb-3">
                            <label for="id_jurusan" class="form-label">Konsentrasi Keahlian</label>
                            <select class="form-select" name="id_jurusan" id="id_jurusan" required>
                                <option value="">Pilih Jurusan...</option>
                                <?php foreach ($data['jurusan'] as $jurusan) : ?>
                                    <option
                                        value="<?= $jurusan->id_jurusan; ?>"
                                        <?= (isset($old_jurusan_id) && $jurusan->id_jurusan == $old_jurusan_id) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($jurusan->jurusan); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="tingkat" class="form-label">Tingkat Kelas</label>
                            <select class="form-select" name="tingkat" id="tingkat" required>
                                <option value="">Pilih Tingkat...</option>
                                <option value="10">X (Sepuluh)</option>
                                <option value="11">XI (Sebelas)</option>
                                <option value="12">XII (Dua Belas)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel-fill"></i> Export ke Excel
                        </button>
                        <a href="<?= BASEURL; ?>/jurusan/" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<?php require_once '../app/views/templates/footer.php'; ?>