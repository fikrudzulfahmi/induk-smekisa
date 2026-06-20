<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>
<div id="main">
    <div class="page-heading">
        <h3>Data Jurusan</h3>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>
        <div class="card">

            <div class="card-header">
                <?php if (Auth::checkRole('admin')) : ?>
                    <a href="<?= BASEURL; ?>/jurusan/tambah" class="btn btn-primary">Tambah Data Jurusan</a>
                <?php endif; ?>
                <a href="<?= BASEURL; ?>/siswa/nominatifOptions" class="btn  btn-success">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export Nominatif Siswa
                </a>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="tabel-jurusan">
                    <thead>
                        <tr>
                            <th>Nama Jurusan</th>
                            <th>Program Keahlian</th>
                            <th>Bidang Keahlian</th>
                            <?php if (Auth::checkRole('admin')) : ?>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once '../app/views/templates/footer.php'; ?>
<script>
    $(document).ready(function() {
        $('#tabel-jurusan').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= BASEURL; ?>/jurusan/getServerSideJurusan",
                "type": "POST"
            },
            "columns": [{
                    "data": "jurusan"
                },
                {
                    "data": "prog_keahlian"
                },
                {
                    "data": "bid_keahlian"
                },
                {
                    "data": "id_jurusan",
                    "orderable": false,
                    "render": function(data) {
                        return `
                        <?php if (Auth::checkRole('admin')) : ?>
                            <a href="<?= BASEURL; ?>/jurusan/edit/${data}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="confirmDelete('<?= BASEURL; ?>/jurusan/hapus/${data}')">Hapus</a>
                        <?php endif; ?>
                        `;
                    }
                }
            ]
        });
    });
</script>