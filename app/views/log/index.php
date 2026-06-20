<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>
<div id="main">
    <div class="page-heading">
        <h3>Activity Log (Catatan Aktivitas Sistem)</h3>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>
        <div class="card">
            <div class="card-header">
                <span class="text-muted">Menampilkan seluruh rekaman aksi pengguna di dalam aplikasi.</span>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover" id="tabel-log" style="width:100%">
                    <thead>
                        <tr>
                            <th>Waktu (WIB)</th>
                            <th>Nama Pengguna</th>
                            <th>Hak Akses / Role</th>
                            <th>Aksi</th>
                            <th>Deskripsi Aktivitas</th>
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
    $('#tabel-log').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [
            [0, "desc"]
        ], // Default: Kolom ke-0 (Waktu) di-sort secara Descending
        "ajax": {
            "url": "<?= BASEURL; ?>/log/getServerSideLog",
            "type": "POST"
        },
        "columns": [{
                "data": "created_at"
            }, // Index 0 -> cocok dengan $columns[0] di model
            {
                "data": "nama_user"
            }, // Index 1 -> cocok dengan $columns[1] di model
            {
                "data": "role", // Index 2 -> cocok dengan $columns[2] di model
                "render": function(data) {
                    let badgeClass = 'bg-secondary';
                    if (data === 'admin') badgeClass = 'bg-danger';
                    else if (data === 'guru') badgeClass = 'bg-primary';
                    else if (data === 'siswa') badgeClass = 'bg-success';
                    return `<span class="badge ${badgeClass}">${data ? data.toUpperCase() : 'GUEST'}</span>`;
                }
            },
            {
                "data": "action", // Index 3 -> cocok dengan $columns[3] di model
                "render": function(data) {
                    let badgeAction = 'bg-secondary';
                    switch (data.toUpperCase()) {
                        case 'LOGIN':
                            badgeAction = 'bg-info';
                            break;
                        case 'LOGOUT':
                            badgeAction = 'bg-dark';
                            break;
                        case 'CREATE':
                            badgeAction = 'bg-success';
                            break;
                        case 'UPDATE':
                            badgeAction = 'bg-warning text-dark';
                            break;
                        case 'DELETE':
                            badgeAction = 'bg-danger';
                            break;
                    }
                    return `<span class="badge ${badgeAction}">${data}</span>`;
                }
            },
            {
                "data": "description"
            } // Index 4 -> cocok dengan $columns[4] di model
        ]
    });
</script>