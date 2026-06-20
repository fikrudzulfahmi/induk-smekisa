<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Daftar Siswa Mutasi Keluar</h3>
                    <p class="text-subtitle text-muted">Siswa yang pindah ke sekolah lain (status: Mutasi Keluar).</p>
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
                        <a href="<?= BASEURL; ?>/mutasi/keluar" class="btn btn-danger me-2"><i class="bi bi-person-dash-fill"></i> Proses Mutasi Keluar Baru</a>
                    <?php endif; ?>
                    <a href="<?= BASEURL; ?>/mutasi" class="btn btn-secondary"> Kembali</a>
                    <?php if (Auth::checkRole('admin')) : ?>
                        <a href="<?= BASEURL; ?>/mutasi/exportExcelKeluar" class="btn btn-success btn-sm float-end">
                            <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered" id="tabelMutasiKeluar">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Keluar</th>
                                <th>Nama Siswa</th>
                                <th>No Induk</th>
                                <th>Sekolah Tujuan</th>
                                <th>Alasan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($data['mutasi_keluar'] as $log) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= tanggal_indo($log->tgl_keluar); ?></td>
                                    <td><?= htmlspecialchars($log->nama_siswa); ?></td>
                                    <td><?= htmlspecialchars($log->no_induk); ?></td>
                                    <td><?= htmlspecialchars($log->sekolah_tujuan); ?></td>
                                    <td><?= htmlspecialchars($log->alasan_keluar); ?></td>
                                    <td>
                                        <a href="<?= BASEURL; ?>/mutasi/editKeluar/<?= $log->id_mutasi_keluar; ?>" class="btn btn-sm btn-warning" title="Edit Log">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="confirmDelete('<?= BASEURL; ?>/mutasi/hapusKeluar/<?= $log->id_mutasi_keluar; ?>')" class="btn btn-sm btn-danger" title="Hapus Log & Aktifkan Siswa">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
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
<script>
    $(document).ready(function() {
        $('#tabelMutasiKeluar').DataTable();
    });
</script>

<?php require_once '../app/views/templates/footer.php'; ?>