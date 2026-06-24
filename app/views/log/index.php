<?php require_once '../app/views/templates/header.php'; ?>
<?php require_once '../app/views/templates/sidebar.php'; ?>
<div id="main">
    <div class="page-heading">
        <h3>Activity Log (Catatan Aktivitas Sistem)</h3>
    </div>
    <div class="page-content">
        <?php Flasher::flash(); ?>

        <div class="card border-start border-4 <?= (isset($data['backup']['status']) && $data['backup']['status'] == 'sukses') ? 'border-success' : 'border-danger'; ?> mb-4 shadow-sm">
            <div class="card-body py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <div class="avatar avatar-lg me-3">
                            <span class="avatar-content <?= (isset($data['backup']['status']) && $data['backup']['status'] == 'sukses') ? 'bg-light-success text-success' : 'bg-light-danger text-danger'; ?> fs-4">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted">Status Auto Backup (Google Drive)</h6>
                            <span class="small font-semibold">
                                <?= isset($data['backup']['keterangan']) ? $data['backup']['keterangan'] : 'Belum ada rekaman backup otomatis harian.'; ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-md-end">
                        <p class="mb-0 text-muted small">Eksekusi Terakhir:</p>
                        <span class="badge <?= (isset($data['backup']['status']) && $data['backup']['status'] == 'sukses') ? 'bg-success' : 'bg-danger'; ?> font-bold">
                            <i class="bi bi-clock-history me-1"></i>
                            <?= isset($data['backup']['created_at']) ? date('d M Y - H:i', strtotime($data['backup']['created_at'])) . ' WIB' : '-'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
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
        ],
        "ajax": {
            "url": "<?= BASEURL; ?>/log/getServerSideLog",
            "type": "POST"
        },
        "columns": [{
                "data": "created_at"
            },
            {
                "data": "nama_user"
            },
            {
                "data": "role",
                "render": function(data) {
                    let badgeClass = 'bg-secondary';
                    if (data === 'admin') badgeClass = 'bg-danger';
                    else if (data === 'guru') badgeClass = 'bg-primary';
                    else if (data === 'siswa') badgeClass = 'bg-success';
                    return `<span class="badge ${badgeClass}">${data ? data.toUpperCase() : 'GUEST'}</span>`;
                }
            },
            {
                "data": "action",
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
            }
        ]
    });
</script>