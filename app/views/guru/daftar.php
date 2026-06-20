<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>
<div id="main">
    <div class="page-heading">
        <h3>Data Guru</h3>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>
        <div class="card">
            <div class="card-header"><a href="<?= BASEURL; ?>/guru/tambah" class="btn btn-primary">Tambah Data Guru</a></div>
            <div class="card-body">
                <table class="table table-striped" id="tabel-guru">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>NUPTK</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Alamat</th>
                            <th>Level</th>
                            <th>Aksi</th>
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
        $('#tabel-guru').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= BASEURL; ?>/guru/getServerSideGuru",
                "type": "POST"
            },
            "columns": [{
                    "data": null,
                    "orderable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "nik"
                },
                {
                    "data": "nuptk"
                },
                {
                    "data": "nama_guru"
                },
                {
                    "data": "username"
                },
                {
                    "data": "alamat"
                },
                {
                    "data": "level_guru"
                },
                {
                    "data": "id_guru",
                    "orderable": false,
                    "render": function(data) {
                        return `<a href="<?= BASEURL; ?>/guru/edit/${data}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="confirmDelete('<?= BASEURL; ?>/guru/hapus/${data}')">Hapus</a>`;
                    }
                }
            ]
        });
    });
</script>