<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>

<div id="main">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Daftar Siswa Mengundurkan Diri</h3>
                    <p class="text-subtitle text-muted">Siswa yang tercatat mengundurkan diri (status: Aktif).</p>
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
                        <a href="<?= BASEURL; ?>/mutasi/keluar" class="btn btn-warning me-2"><i class="bi bi-flag-fill"></i> Proses Mengundurkan Diri Baru</a>
                    <?php endif; ?>
                    <a href="<?= BASEURL; ?>/mutasi" class="btn btn-secondary"> Kembali</a>
                    <?php if (Auth::checkRole('admin')) : ?>
                        <a href="<?= BASEURL; ?>/mutasi/exportExcelMengundurkanDiri" class="btn btn-success btn-sm float-end">
                            <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered" id="tabelUndurDiri">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Undur Diri</th>
                                <th>Nama Siswa</th>
                                <th>No Induk</th>
                                <th>Status</th>
                                <th>Alasan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($data['mengundurkan_diri'] as $log) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= tanggal_indo($log->tgl_mengundurkan_diri); ?></td>
                                    <td><?= htmlspecialchars($log->nama_siswa); ?></td>
                                    <td><?= htmlspecialchars($log->no_induk); ?></td>
                                    <td><?= htmlspecialchars($log->status); ?></td>
                                    <td><?= htmlspecialchars($log->alasan_mengundurkan_diri); ?></td>
                                    <td>
                                        <a href="<?= BASEURL; ?>/mutasi/editMengundurkanDiri/<?= $log->id_mengundurkan_diri; ?>" class="btn btn-sm btn-warning" title="Edit Log">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="confirmDelete('<?= BASEURL; ?>/mutasi/hapusMengundurkanDiri/<?= $log->id_mengundurkan_diri; ?>')" class="btn btn-sm btn-danger" title="Hapus Log">
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
        $('#tabelUndurDiri').DataTable();
    });
</script>

<?php require_once '../app/views/templates/footer.php'; ?>