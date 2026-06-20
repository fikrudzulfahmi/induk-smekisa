<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Daftar Siswa Mutasi Masuk</h3>
                    <p class="text-subtitle text-muted">Siswa yang masuk melalui jalur pindahan.</p>
                </div>

            </div>
        </div>
    </div>

    <div class="page-content">
        <?php Flasher::flash(); ?>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <?php if (Auth::checkRole('admin')) : ?>
                        <a href="<?= BASEURL; ?>/mutasi/masuk" class="btn btn-primary me-2"><i class="bi bi-person-plus-fill"></i> Tambah Data Mutasi Masuk</a>
                    <?php endif; ?>
                    <a href="<?= BASEURL; ?>/mutasi" class="btn btn-secondary"> Kembali</a>
                    <?php if (Auth::checkRole('admin')) : ?>
                        <a href="<?= BASEURL; ?>/mutasi/exportExcelMasuk" class="btn btn-success btn-sm float-end">
                            <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                        </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <table class="table table-striped table-bordered" id="tabelMutasiMasuk">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Diterima</th>
                                <th>Nama Siswa</th>
                                <th>No Induk</th>
                                <th>Asal Sekolah</th>
                                <th>Alasan Pindah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($data['mutasi_masuk'] as $siswa) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= tanggal_indo($siswa->tgl_diterima); // Gunakan helper tanggal 
                                        ?></td>
                                    <td><?= htmlspecialchars($siswa->nama_siswa); ?></td>
                                    <td><?= htmlspecialchars($siswa->no_induk); ?></td>
                                    <td><?= htmlspecialchars($siswa->asal_sekolah); ?></td>
                                    <td><?= htmlspecialchars($siswa->alasan_pindah); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="<?= BASEURL; ?>/assets/extensions/jquery/jquery.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="<?= BASEURL; ?>/assets/static/js/pages/datatables.js"></script>
<script>
    $(document).ready(function() {
        $('#tabelMutasiMasuk').DataTable();
    });
</script>

<?php require_once '../app/views/templates/footer.php'; ?>